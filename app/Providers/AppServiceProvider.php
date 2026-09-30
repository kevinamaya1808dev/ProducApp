<?php

namespace App\Providers;

use App\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // Bypass global: el administrador tiene acceso total a todos los Gates.
        // Ya no hay excepción para Operario: ese módulo se resuelve por rol
        // directamente (hasRole('operario')) donde se necesite, no por permiso.
        Gate::before(function (User $user, string $ability) {
            return $user->hasRole('admin') ? true : null;
        });

        // Registro dinámico: cada fila de la tabla `permissions` (módulo/acción)
        // se convierte automáticamente en un Gate. Basta con crearlo en el
        // seeder/código y queda disponible para @can, can: en rutas y $user->can().
        Permission::all()->each(function (Permission $permission) {
            Gate::define($permission->slug, fn (User $user) => $user->hasPermission($permission->slug));
        });
    }
}