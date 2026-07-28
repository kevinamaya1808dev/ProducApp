<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // Definición del Gate para el módulo de productos
        Gate::define('access-products', function (User $user) {
            if ($user->role === 'admin') {
                return true; // El admin siempre tiene acceso
            }

            if ($user->role === 'becario' && $user->can_access_products) {
                return true; // Becario autorizado por el admin
            }

            return false;
        });

        Gate::before(function ($user, $ability) {
        if ($user->role === 'admin') {
            return true;
        }
    });
    }
}