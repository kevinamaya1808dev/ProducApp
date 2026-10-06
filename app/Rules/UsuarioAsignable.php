<?php

namespace App\Rules;

use App\Models\User;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

/**
 * Un usuario con rol de administrador NO puede asignarse a órdenes ni
 * subórdenes (ni como encargado ni como operario). Solo se valida la
 * asignación: el rol del usuario no se modifica en ningún momento.
 */
class UsuarioAsignable implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $user = User::find($value);

        if ($user && $user->hasRole('admin')) {
            $fail('Un administrador no puede ser asignado a órdenes ni subórdenes.');
        }
    }
}