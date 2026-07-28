<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index()
    {
        $totalUsers = User::count();

        $users = User::with(['roles', 'permissions']) // Cargamos los permisos para evitar consultas N+1
            ->where('id', '!=', auth()->id())
            ->get();

        $roles = \App\Models\Role::all();
        $permissions = \App\Models\Permission::all(); // AQUÍ: Extraemos los permisos

        // Enviamos los permisos a la vista
        return view('admin.users.index', compact('users', 'roles', 'permissions', 'totalUsers')); 
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:8',
            'role_id'  => 'required|exists:roles,id',
        ], [
            'name.required'     => 'El nombre es obligatorio.',
            'email.required'    => 'El correo es obligatorio.',
            'email.unique'      => 'Ese correo ya está registrado.',
            'password.required' => 'La contraseña es obligatoria.',
            'password.min'      => 'La contraseña debe tener al menos 8 caracteres.',
            'role_id.required'  => 'Debes asignar un rol.',
        ]);

        $user = User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        $user->roles()->sync([$validated['role_id']]);

        return back()->with('success', "Usuario '{$user->name}' creado correctamente.");
    }

   public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'email'       => ['required', 'email', 'max:255', \Illuminate\Validation\Rule::unique('users', 'email')->ignore($user->id)],
            'password'    => 'nullable|string|min:8',
            'role_id'     => 'required|exists:roles,id',
            'permissions' => 'nullable|array', // Validamos que llegue un array de permisos
            'permissions.*' => 'exists:permissions,id' // Validamos que los permisos existan
        ], [
            // ... tus mensajes de error ...
        ]);

        $user->name  = $validated['name'];
        $user->email = $validated['email'];

        if (!empty($validated['password'])) {
            $user->password = \Illuminate\Support\Facades\Hash::make($validated['password']);
        }

        $user->save();
        
        // Sincronizamos el Rol
        $user->roles()->sync([$validated['role_id']]);
        
        // AQUÍ ESTÁ LA MAGIA: Sincronizamos los permisos en la base de datos
        // Si no mandan nada, sincronizamos un array vacío para borrarle permisos anteriores
        $user->permissions()->sync($request->permissions ?? []); 

        return back()->with('success', "Usuario '{$user->name}' actualizado correctamente.");
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'No puedes eliminar tu propia cuenta.');
        }

        $name = $user->name;
        $user->roles()->detach();
        $user->delete();

        return back()->with('success', "Usuario '{$name}' eliminado correctamente.");
    }

    // Activar o desactivar permiso de Productos
    public function toggleProductAccess(User $user)
    {
        $user->can_access_products = !$user->can_access_products;
        $user->save();

        return back()->with('success', "Permisos de '{$user->name}' actualizados.");
    }

    // Cambiar rol de usuario (ahora vía tabla pivote role_user)
    public function updateRole(Request $request, User $user)
    {
        $request->validate([
            'role_id' => 'required|exists:roles,id',
        ]);

        $user->roles()->sync([$request->role_id]);

        return back()->with('success', "Rol de '{$user->name}' actualizado.");
    }
}