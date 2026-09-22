<?php

namespace App\Providers;

use App\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    // Abilities del módulo Operario: quedan FUERA del bypass de admin,
    // se evalúan siempre por permiso real (aunque el usuario sea admin).
    private const OPERARIO_ABILITIES = [
        'access-operario',
        'view-assigned-orders',
        'update-progress',
        'create-incidences',
    ];

    public function boot(): void
    {
        // Bypass global: el administrador tiene acceso total a todos los Gates,
        // EXCEPTO a las abilities del módulo Operario.
        Gate::before(function (User $user, string $ability) {
            if ($user->hasRole('admin') && !in_array($ability, self::OPERARIO_ABILITIES)) {
                return true;
            }

            return null;
        });

        // Registro dinámico: cada fila de la tabla `permissions` se convierte
        // automáticamente en un Gate. Ya no hace falta tocar este archivo
        // cada vez que se crea un permiso nuevo desde el CRUD — basta con
        // crearlo ahí y queda disponible para @can, can: en rutas y $user->can().
        Permission::all()->each(function (Permission $permission) {
            Gate::define($permission->slug, fn (User $user) => $user->hasPermission($permission->slug));
        });
    }
}