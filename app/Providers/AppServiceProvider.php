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
        // Intercepta TODAS las validaciones de permisos (@can, middleware can:, etc.)
        Gate::before(function ($user, $ability) {
            // Si es Super Admin, tiene acceso a todo automáticamente sin revisar la BD
            if ($user->hasRole('admin')) {
                return true;
            }

            // Para operarios y becarios, usamos la función de tu modelo
            if ($user->hasPermission($ability)) {
                return true;
            }
            
            // Retornar null permite que otras comprobaciones sigan su curso
            return null;
        }); // <-- AQUÍ: Faltaba cerrar el paréntesis y poner el punto y coma
    }
} // <-- AQUÍ: Faltaba la llave para cerrar la clase