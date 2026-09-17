<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // Abilities del módulo Operario: quedan FUERA del bypass de admin,
        // se evalúan siempre por permiso real (aunque el usuario sea admin)
        $operarioAbilities = [
            'access-operario',
            'view-assigned-orders',
            'update-progress',
            'create-incidences',
        ];

        // Bypass global: el administrador tiene acceso total a todos los Gates,
        // EXCEPTO a las abilities del módulo Operario
        Gate::before(function (User $user, string $ability) use ($operarioAbilities) {
            if ($user->hasRole('admin') && !in_array($ability, $operarioAbilities)) {
                return true;
            }

            return null; // deja que Gate::define evalúe normalmente
        });

        // MÓDULO ADMINISTRATIVO
        Gate::define('view-admin-dashboard', fn (User $user) => $user->hasPermission('view-admin-dashboard'));
        
        // Productos
        Gate::define('view-products', fn (User $user) => $user->hasPermission('view-products') || $user->hasPermission('access-products'));
        Gate::define('access-products', fn (User $user) => $user->hasPermission('access-products') || $user->hasPermission('view-products'));
        Gate::define('manage-products', fn (User $user) => $user->hasPermission('manage-products'));

        // Categorías
        Gate::define('view-categories', fn (User $user) => $user->hasPermission('view-categories'));
        Gate::define('manage-categories', fn (User $user) => $user->hasPermission('manage-categories'));

        // Almacén e Insumos
        Gate::define('view-almacen', fn (User $user) => $user->hasPermission('view-almacen'));
        Gate::define('manage-almacen', fn (User $user) => $user->hasPermission('manage-almacen'));

        // Proveedores
        Gate::define('view-proveedores', fn (User $user) => $user->hasPermission('view-proveedores'));
        Gate::define('manage-proveedores', fn (User $user) => $user->hasPermission('manage-proveedores'));

        // Recetas
        Gate::define('view-recipes', fn (User $user) => $user->hasPermission('view-recipes'));
        Gate::define('manage-recipes', fn (User $user) => $user->hasPermission('manage-recipes'));

        // Órdenes y Usuarios
        Gate::define('view-orders', fn (User $user) => $user->hasPermission('view-orders'));
        Gate::define('manage-orders', fn (User $user) => $user->hasPermission('manage-orders'));
        Gate::define('view-users', fn (User $user) => $user->hasPermission('view-users'));
        Gate::define('manage-users', fn (User $user) => $user->hasPermission('manage-users'));

        // MÓDULO OPERARIO
        Gate::define('access-operario', fn (User $user) => $user->hasPermission('access-operario'));
        Gate::define('view-assigned-orders', fn (User $user) => $user->hasPermission('view-assigned-orders'));
        Gate::define('update-progress', fn (User $user) => $user->hasPermission('update-progress'));
        Gate::define('create-incidences', fn (User $user) => $user->hasPermission('create-incidences'));
    }
}