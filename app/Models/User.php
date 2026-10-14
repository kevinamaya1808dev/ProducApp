<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'puesto',
        'turno',
        'estacion',
        'active',
        'meta_diaria',
        'notas',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'active' => 'boolean',
        ];
    }

    // ==========================================
    // RELACIONES ELOQUENT
    // ==========================================
    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    public function permissions()
    {
        return $this->belongsToMany(Permission::class);
    }

    public function incidences()
    {
        return $this->hasMany(Incidence::class);
    }

    public function productionOrders()
    {
        return $this->hasMany(ProductionOrder::class, 'user_id');
    }

    public function registrosProduccion()
    {
        return $this->hasMany(RegistroProduccion::class);
    }

    public function certifications()
    {
        return $this->hasMany(UserCertification::class);
    }

    public function stockLogs(): HasMany
    {
        return $this->hasMany(MaterialStockLog::class);
    }

    // Usuarios que pueden asignarse a órdenes/subórdenes: todos excepto administradores.
    public function scopeAsignables($query)
    {
        return $query->whereDoesntHave('roles', function ($q) {
            $q->where('slug', 'admin');
        });
    }

    // ==========================================
    // MÉTODOS DE VALIDACIÓN (RBAC ROBUSTOS)
    // ==========================================

    /**
     * Roles y permisos efectivos del usuario (directos + los de sus roles),
     * cargados UNA vez por instancia con 3 consultas. Antes cada hasRole() /
     * hasPermission() ejecutaba 1-2 consultas, y cada @can del sidebar y de
     * las vistas las repetía: entre 12 y 40 consultas extra por página.
     *
     * Equivale a la lógica anterior: coincide por slug o por nombre, sin
     * distinguir mayúsculas (igual que el = / LIKE de la BD).
     */
    private ?array $autorizacion = null;

    private function autorizacion(): array
    {
        if ($this->autorizacion === null) {
            $roles    = $this->roles()->get(['roles.id', 'roles.slug', 'roles.name']);
            $directos = $this->permissions()->get(['permissions.slug', 'permissions.name']);
            $porRol   = $roles->isEmpty()
                ? collect()
                : DB::table('permission_role')
                    ->join('permissions', 'permissions.id', '=', 'permission_role.permission_id')
                    ->whereIn('permission_role.role_id', $roles->pluck('id'))
                    ->get(['permissions.slug', 'permissions.name']);

            $conjunto = fn ($filas) => $filas
                ->flatMap(fn ($f) => [mb_strtolower((string) $f->slug), mb_strtolower((string) $f->name)])
                ->filter()
                ->flip()
                ->all();

            $this->autorizacion = [
                'roles'    => $conjunto($roles),
                'permisos' => $conjunto($directos->concat($porRol)),
            ];
        }

        return $this->autorizacion;
    }

    /** Descarta la caché (úsalo tras cambiar los roles/permisos de esta misma instancia). */
    public function flushAuthorizationCache(): void
    {
        $this->autorizacion = null;
    }

    public function refresh()
    {
        $this->autorizacion = null;

        return parent::refresh();
    }

    public function hasRole(string $role): bool
    {
        return isset($this->autorizacion()['roles'][mb_strtolower($role)]);
    }

    public function hasPermission(string $permission): bool
    {
        return isset($this->autorizacion()['permisos'][mb_strtolower($permission)]);
    }
}
