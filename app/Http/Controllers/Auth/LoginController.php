<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected function redirectTo()
    {
        return auth()->user()->hasRole('admin')
            ? route('admin.dashboard')
            : route('operario.inicio');
    }

    // Solo pueden iniciar sesión los usuarios activos (no dados de baja).
    protected function credentials(Request $request)
    {
        return array_merge($request->only($this->username(), 'password'), ['active' => 1]);
    }

    // Mensaje genérico: no revela si la cuenta existe o está dada de baja.
    protected function sendFailedLoginResponse(Request $request)
    {
        throw ValidationException::withMessages([
            $this->username() => ['Credenciales incorrectas o cuenta dada de baja.'],
        ]);
    }

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
}