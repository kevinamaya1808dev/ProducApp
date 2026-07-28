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
    // MÉTODOS DE VALIDACIÓN (RBAC)
    // ==========================================

    public function hasRole(string $roleSlug): bool
    {
        return $this->roles()->where('slug', $roleSlug)->exists();
    }

    /**
     * Función maestra para verificar permisos.
     * Revisa primero permisos directos del usuario, y si no encuentra,
     * revisa los permisos heredados por su(s) rol(es).
     */
    public function hasPermission(string $permissionSlug): bool
    {
        // Permiso asignado directamente al usuario
        if ($this->permissions()->where('slug', $permissionSlug)->exists()) {
            return true;
        }

        // Permiso heredado por rol (evita N+1)
        return $this->roles()->whereHas('permissions', function ($query) use ($permissionSlug) {
            $query->where('slug', $permissionSlug);
        })->exists();
    }
}