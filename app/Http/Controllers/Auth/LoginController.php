<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    /**
     * Redirección dinámica según el rol del usuario tras iniciar sesión.
     */
    protected function redirectTo()
{
    $user = auth()->user();

    // Si tu columna en la tabla 'users' es 'role' (string)
    if ($user->role === 'admin') {
        return route('admin.dashboard');
    }

    // Si usas relaciones de Eloquent con la tabla 'roles' (como vimos previamente)
    if ($user->roles && $user->roles->contains('slug', 'admin')) {
        return route('admin.dashboard');
    }

    return route('operario.inicio');
}

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
}