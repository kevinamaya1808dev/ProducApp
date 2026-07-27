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
        if (Auth::user()->role === 'admin') {
            return route('admin.dashboard');
        }

        return route('operario.inicio');
    }

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
}