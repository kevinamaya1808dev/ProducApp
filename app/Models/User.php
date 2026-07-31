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
        'planta', // Estación de trabajo
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

    public function skills()
    {
        return $this->hasMany(UserSkill::class);
    }

    public function certifications()
    {
        return $this->hasMany(UserCertification::class);
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

    public function hasPermission(string $permission): bool
    {
        $hasDirect = $this->permissions()->where(function ($query) use ($permission) {
            $query->where('slug', $permission)
                  ->orWhere('name', $permission);
        })->exists();

        if ($hasDirect) {
            return true;
        }

        return $this->roles()->whereHas('permissions', function ($query) use ($permission) {
            $query->where('slug', $permission)
                  ->orWhere('name', $permission);
        })->exists();
    }
}