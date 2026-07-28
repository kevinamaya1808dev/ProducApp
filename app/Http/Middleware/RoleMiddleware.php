<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string $role): Response
    {
        // 1. Verificar si el usuario está autenticado
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        // 2. Verificar el rol usando la relación de la tabla pivote (roles)
        // Se incluye también $user->role como alternativa por retrocompatibilidad
        $hasRole = $user->roles()->where('slug', $role)->exists() 
                || $user->role === $role;

        if (!$hasRole) {
            abort(403, 'No tienes permiso para acceder a esta área.');
        }

        return $next($request);
    }
}