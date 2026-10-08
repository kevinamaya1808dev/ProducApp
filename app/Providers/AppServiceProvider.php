<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // Un único Gate::before resuelve TODOS los permisos:
        //  - El administrador tiene acceso total.
        //  - Para cualquier otro usuario, la habilidad ("modulo.accion") es un
        //    slug de la tabla `permissions` y se valida bajo demanda.
        Gate::before(function (User $user, string $ability) {
            if ($user->hasRole('admin')) {
                return true;
            }

            return $user->hasPermission($ability) ?: null;
        });
    }
}