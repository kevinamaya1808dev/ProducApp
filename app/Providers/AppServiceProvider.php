<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // 1. INTERCEPTOR GLOBAL (Super Admin)
        Gate::before(function ($user, $ability) {
            // Si es Administrador, tiene acceso total a todo de forma automática
            if ($user->hasRole('admin')) {
                return true;
            }

            // Retornamos null para que Laravel siga evaluando los Gates explícitos de abajo
            return null;
        });

        // ==========================================
        // 2. REGISTRO EXPLÍCITO DE GATES (Basado en DB)
        // ==========================================
        
        // ID 1: Acceso a Productos
        Gate::define('access-products', function (User $user) {
            return $user->hasPermission('access-products');
        });

        // ID 2: Ver Órdenes
        Gate::define('view-orders', function (User $user) {
            return $user->hasPermission('view-orders');
        });

        // ID 3: Gestionar Órdenes
        Gate::define('manage-orders', function (User $user) {
            return $user->hasPermission('manage-orders');
        });

        // ID 4: Ver Dashboard Admin
        Gate::define('view-admin-dashboard', function (User $user) {
            return $user->hasPermission('view-admin-dashboard');
        });

        // ID 5: Gestionar Categorías
        Gate::define('manage-categories', function (User $user) {
            return $user->hasPermission('manage-categories');
        });

        // ID 6: Gestionar Usuarios
        Gate::define('manage-users', function (User $user) {
            return $user->hasPermission('manage-users');
        });

        // ID 7: Gestionar Recetas
        Gate::define('manage-recipes', function (User $user) {
            return $user->hasPermission('manage-recipes');
        });

        // ID 8: Acceso Módulo Operario
        Gate::define('access-operario', function (User $user) {
            return $user->hasPermission('access-operario');
        });

        // ID 9: Ver Órdenes Asignadas
        Gate::define('view-assigned-orders', function (User $user) {
            return $user->hasPermission('view-assigned-orders');
        });

        // ID 10: Actualizar Progreso
        Gate::define('update-progress', function (User $user) {
            return $user->hasPermission('update-progress');
        });

        // ID 11: Reportar Incidencias
        Gate::define('create-incidences', function (User $user) {
            return $user->hasPermission('create-incidences');
        });
    }
}