<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use App\Models\UserSkill;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index()
    {
        $totalUsers = User::count();

        // Se obtienen todos los usuarios sin omitir al usuario autenticado
        $users = User::with(['roles', 'permissions', 'productionOrders', 'skills'])->get();

        $roles = Role::all();
        $permissions = Permission::all();

        return view('admin.users.index', compact('users', 'roles', 'permissions', 'totalUsers')); 
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'          => 'required|string|max:255',
            'email'         => 'required|email|unique:users,email',
            'password'      => 'required|string|min:8',
            'role_id'       => 'required|exists:roles,id',
            'active'        => 'required|boolean',
            'puesto'        => 'nullable|string|max:100',
            'turno'         => 'nullable|string|max:50',
            'estacion'      => 'nullable|string|max:100',
            'meta_diaria'   => 'nullable|integer|min:0',
            'notas'         => 'nullable|string',
            'permissions'   => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
            'skills'        => 'nullable|array',
        ]);

        $user = User::create([
            'name'        => $validated['name'],
            'email'       => $validated['email'],
            'password'    => Hash::make($validated['password']),
            'puesto'      => $validated['puesto'] ?? null,
            'turno'       => $validated['turno'] ?? null,
            'estacion'    => $validated['estacion'] ?? null,
            'active'      => $validated['active'],
            'meta_diaria' => $validated['meta_diaria'] ?? null,
            'notas'       => $validated['notas'] ?? null,
        ]);

        $user->roles()->sync([$validated['role_id']]);

        if (!empty($validated['permissions'])) {
            $user->permissions()->sync($validated['permissions']);
        }

        if (!empty($validated['skills'])) {
            foreach ($validated['skills'] as $skillName) {
                UserSkill::create([
                    'user_id' => $user->id,
                    'skill'   => $skillName,
                ]);
            }
        }

        return redirect()->route('admin.users.index')->with('success', "Operario '{$user->name}' creado exitosamente.");
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name'          => 'required|string|max:255',
            'email'         => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'password'      => 'nullable|string|min:8',
            'role_id'       => 'required|exists:roles,id',
            'active'        => 'required|boolean',
            'puesto'        => 'nullable|string|max:100',
            'turno'         => 'nullable|string|max:50',
            'estacion'      => 'nullable|string|max:100',
            'meta_diaria'   => 'nullable|integer|min:0',
            'notas'         => 'nullable|string',
            'permissions'   => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
            'skills'        => 'nullable|array',
        ]);

        $user->name        = $validated['name'];
        $user->email       = $validated['email'];
        $user->puesto      = $validated['puesto'] ?? null;
        $user->turno       = $validated['turno'] ?? null;
        $user->estacion    = $validated['estacion'] ?? null;
        $user->active      = $validated['active'];
        $user->meta_diaria = $validated['meta_diaria'] ?? null;
        $user->notas       = $validated['notas'] ?? null;

        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        $user->roles()->sync([$validated['role_id']]);
        $user->permissions()->sync($validated['permissions'] ?? []);

        $user->skills()->delete();
        if (!empty($validated['skills'])) {
            foreach ($validated['skills'] as $skillName) {
                UserSkill::create([
                    'user_id' => $user->id,
                    'skill'   => $skillName,
                ]);
            }
        }

        return back()->with('success', "Usuario '{$user->name}' actualizado correctamente.");
    }

    public function destroy(User $user)
    {
        // Protege al administrador raíz (ID 1) de ser eliminado
        if ($user->id === 1) {
            return back()->with('error', 'El administrador principal (ID 1) no se puede eliminar.');
        }

        // Protege al usuario actual de auto-eliminarse
        if ($user->id === auth()->id()) {
            return back()->with('error', 'No puedes eliminar tu propia cuenta en uso.');
        }

        $name = $user->name;
        $user->roles()->detach();
        $user->permissions()->detach();
        $user->skills()->delete();
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