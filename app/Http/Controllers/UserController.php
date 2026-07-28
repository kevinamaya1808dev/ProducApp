<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $users = User::where('id', '!=', auth()->id())->get();
        return view('admin.users.index', compact('users'));
    }

    // Activar o desactivar permiso de Productos
    public function toggleProductAccess(User $user)
    {
        $user->can_access_products = !$user->can_access_products;
        $user->save();

        return back()->with('success', "Permisos de '{$user->name}' actualizados.");
    }

    // Cambiar rol de usuario
    public function updateRole(Request $request, User $user)
    {
        $request->validate([
            'role' => 'required|in:admin,becario,operario',
        ]);

        $user->role = $request->role;
        $user->save();

        return back()->with('success', "Rol de '{$user->name}' cambiado a {$user->role}.");
    }
}