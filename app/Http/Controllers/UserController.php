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

        // Agregamos 'productionOrders' en el with() para disponibilizar las órdenes en la vista
        $users = User::with(['roles', 'permissions', 'productionOrders'])
            ->where('id', '!=', auth()->id())
            ->get();

        $roles = \App\Models\Role::all();
        $permissions = \App\Models\Permission::all();

        return view('admin.users.index', compact('users', 'roles', 'permissions', 'totalUsers')); 
    }

   public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'email'       => 'required|email|unique:users,email',
            'password'    => 'required|string|min:8',
            'role_id'     => 'required|exists:roles,id',
            'active'      => 'required|boolean',
            'turno'       => 'nullable|string|max:50',
            'planta'      => 'nullable|string|max:100',
            'notas'       => 'nullable|string',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
            'skills'      => 'nullable|array',
        ]);

        $user = User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'password' => Hash::make($validated['password']),
            'turno'    => $validated['turno'] ?? null,
            'planta'   => $validated['planta'] ?? null,
            'active'   => $validated['active'],
            'notas'    => $validated['notas'] ?? null,
        ]);

        $user->roles()->sync([$validated['role_id']]);
        
        if (!empty($validated['permissions'])) {
            $user->permissions()->sync($validated['permissions']);
        }

        if (!empty($validated['skills'])) {
            foreach ($validated['skills'] as $skillName) {
                \App\Models\UserSkill::create([
                    'user_id' => $user->id,
                    'skill'   => $skillName
                ]);
            }
        }

        return redirect()->route('admin.users.index')->with('success', "Operario '{$user->name}' creado exitosamente.");
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'email'       => ['required', 'email', 'max:255', \Illuminate\Validation\Rule::unique('users', 'email')->ignore($user->id)],
            'password'    => 'nullable|string|min:8',
            'role_id'     => 'required|exists:roles,id',
            'turno'       => 'nullable|string|max:50',
            'planta'      => 'nullable|string|max:100',
            'active'      => 'required|boolean',
            'notas'       => 'nullable|string',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
            'skills'      => 'nullable|array',
        ]);

        $user->name   = $validated['name'];
        $user->email  = $validated['email'];
        $user->turno  = $validated['turno'] ?? null;
        $user->planta = $validated['planta'] ?? null;
        $user->active = $validated['active'];
        $user->notas  = $validated['notas'] ?? null;

        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();
        
        $user->roles()->sync([$validated['role_id']]);
        $user->permissions()->sync($validated['permissions'] ?? []); 

        $user->skills()->delete();
        if (!empty($validated['skills'])) {
            foreach ($validated['skills'] as $skillName) {
                \App\Models\UserSkill::create([
                    'user_id' => $user->id,
                    'skill'   => $skillName
                ]);
            }
        }

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

    public function toggleProductAccess(User $user)
    {
        $user->can_access_products = !$user->can_access_products;
        $user->save();

        return back()->with('success', "Permisos de '{$user->name}' actualizados.");
    }

    public function updateRole(Request $request, User $user)
    {
        $request->validate([
            'role_id' => 'required|exists:roles,id',
        ]);

        $user->roles()->sync([$request->role_id]);

        return back()->with('success', "Rol de '{$user->name}' actualizado.");
    }
}