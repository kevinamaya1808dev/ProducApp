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
            // Si es Administrador, tiene acceso total automáticamente
            if ($user->hasRole('admin')) {
                return true;
            }

            // Validación general de permisos asignados al operario
            if ($user->hasPermission($ability)) {
                return true;
            }
            
            return null;
        });

        // 2. REGISTRO EXPLÍCITO DE GATES PARA RUTAS Y VISTAS
        
        // Módulo de Productos
        Gate::define('access-products', function (User $user) {
            return $user->hasPermission('access-products');
        });

        // Módulo de Recetas
        Gate::define('view-recipes', function (User $user) {
            return $user->hasPermission('view-recipes');
        });
        Gate::define('manage-recipes', function (User $user) {
            return $user->hasPermission('manage-recipes');
        });
        Gate::define('gestionar-recetas', function (User $user) {
            return $user->hasPermission('gestionar-recetas');
        });

        // Módulo de Órdenes
        Gate::define('manage-orders', function (User $user) {
            return $user->hasPermission('manage-orders');
        });
        Gate::define('gestionar-ordenes', function (User $user) {
            return $user->hasPermission('gestionar-ordenes');
        });

        // Módulo de Usuarios / Operarios
        Gate::define('gestionar-usuarios', function (User $user) {
            return $user->hasPermission('gestionar-usuarios');
        });

        // Módulo de Configuración del Sistema
        Gate::define('gestionar-configuracion', function (User $user) {
            return $user->hasPermission('gestionar-configuracion');
        });
    }
}