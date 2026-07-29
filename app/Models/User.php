<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
    'name',
    'email',
    'password',
    'puesto',
    'turno',
    'planta',
    'active',
    'meta_diaria',
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
        ];
    }

    // ==========================================
    // RELACIONES ELOQUENT
    // ==========================================

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    /**
     * Permisos asignados DIRECTAMENTE al usuario (override individual,
     * independiente del rol que tenga asignado).
     */
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
        return $this->hasMany(ProductionOrder::class);
    }

    public function registrosProduccion()
    {
        return $this->hasMany(RegistroProduccion::class);
    }

   // ==========================================
    // MÉTODOS DE VALIDACIÓN (RBAC ROBUSTOS)
    // ==========================================

    public function hasRole(string $role): bool
    {
        return $this->roles()->where(function ($query) use ($role) {
            $query->where('slug', $role)
                  ->orWhere('name', $role)
                  ->orWhere('name', 'LIKE', $role);
        })->exists();
    }

    /**
     * Función maestra para verificar permisos.
     * Revisa primero permisos directos, y luego los heredados por rol.
     */
    public function hasPermission(string $permission): bool
    {
        // 1. Permiso asignado directamente al usuario (buscando por slug o name)
        $hasDirect = $this->permissions()->where(function ($query) use ($permission) {
            $query->where('slug', $permission)
                  ->orWhere('name', $permission);
        })->exists();

        if ($hasDirect) {
            return true;
        }

        // 2. Permiso heredado por rol
        return $this->roles()->whereHas('permissions', function ($query) use ($permission) {
            $query->where('slug', $permission)
                  ->orWhere('name', $permission);
        })->exists();
    }

public function skills()
{
    return $this->hasMany(UserSkill::class);
}

public function certifications()
{
    return $this->hasMany(UserCertification::class);
}

}