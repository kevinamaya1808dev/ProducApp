<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // MÓDULO ADMINISTRATIVO
        Gate::define('view-admin-dashboard', fn (User $user) => $user->hasPermission('view-admin-dashboard'));
        
        // Productos
        Gate::define('view-products', fn (User $user) => $user->hasPermission('view-products') || $user->hasPermission('access-products'));
        Gate::define('access-products', fn (User $user) => $user->hasPermission('access-products') || $user->hasPermission('view-products'));
        Gate::define('manage-products', fn (User $user) => $user->hasPermission('manage-products'));

        // Categorías
        Gate::define('view-categories', fn (User $user) => $user->hasPermission('view-categories'));
        Gate::define('manage-categories', fn (User $user) => $user->hasPermission('manage-categories'));

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