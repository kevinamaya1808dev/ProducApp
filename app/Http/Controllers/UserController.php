<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class UserController extends Controller
{
    private function userRules(?int $ignoreId = null): array
    {
        return [
            'name'        => 'required|string|max:255',
            'email'       => ['required', 'email', 'max:255', $ignoreId
                ? Rule::unique('users', 'email')->ignore($ignoreId)
                : 'unique:users,email'],
            'password'    => $ignoreId ? 'nullable|string|min:8' : 'required|string|min:8',
            'role_id'     => 'required|exists:roles,id',
            'active'      => 'required|boolean',
            'puesto'      => 'nullable|string|max:100',
            'turno'       => 'nullable|string|max:50',
            'estacion'    => 'nullable|string|max:100',
            'meta_diaria' => 'nullable|integer|min:0',
        ];
    }

    private function fillUser(User $user, array $data): User
    {
        $user->fill([
            'name'        => $data['name'],
            'email'       => $data['email'],
            'puesto'      => $data['puesto']      ?? null,
            'turno'       => $data['turno']       ?? null,
            'estacion'    => $data['estacion']    ?? null,
            'active'      => $data['active'],
            'meta_diaria' => $data['meta_diaria'] ?? null,
        ]);

        if (!empty($data['password'])) {
            $user->password = Hash::make($data['password']);
        }

        return $user;
    }

    private function tieneHistorialDeProduccion(User $user): bool
    {
        return $user->productionOrders()->exists()
            || $user->registrosProduccion()->exists()
            || $user->incidences()->exists()
            || DB::table('production_sub_order_user')->where('user_id', $user->id)->exists();
    }

    private function anonimizarUsuario(User $user): void
    {
        $user->forceFill([
            'name'        => 'Usuario eliminado #' . $user->id,
            'email'       => 'usuario-eliminado-' . $user->id . '@baja.local',
            'password'    => Hash::make(Str::random(40)),
            'puesto'      => null,
            'turno'       => null,
            'estacion'    => null,
            'notas'       => null,
            'meta_diaria' => null,
            'active'      => false,
        ])->save();

        $user->roles()->detach();
        $user->permissions()->detach();
    }

    public function index(): View
    {
        return view('admin.users.index', [
            'totalUsers' => User::count(),
            'users'      => User::with(['roles', 'permissions', 'productionOrders'])->get(),
            'roles'      => Role::all(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate($this->userRules());
        $user = $this->fillUser(new User, $data);
        $user->save();
        $user->roles()->sync([$data['role_id']]);

        return redirect()->route('admin.users.permissions.edit', $user)
            ->with('success', "Operario '{$user->name}' creado exitosamente. Ahora puedes asignarle permisos.");
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $data = $request->validate($this->userRules($user->id));
        $this->fillUser($user, $data)->save();
        $user->roles()->sync([$data['role_id']]);

        return back()->with('success', "Usuario '{$user->name}' actualizado correctamente.");
    }

    public function editPermissions(User $user): View
    {
        $permissions = Permission::orderBy('module')->orderBy('action')->get();

        return view('admin.users.permissions', [
            'user'               => $user,
            'permissionModules'  => $permissions->where('is_special', false)->groupBy('module'),
            'specialPermissions' => $permissions->where('is_special', true),
            'selectedIds'        => $user->permissions->pluck('id')->toArray(),
        ]);
    }

    public function updatePermissions(Request $request, User $user): RedirectResponse
    {
        $data = $request->validate([
            'permissions'   => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        $user->permissions()->sync($data['permissions'] ?? []);

        return redirect()->route('admin.users.index')
            ->with('success', "Permisos de '{$user->name}' actualizados correctamente.");
    }

    public function destroy(User $user): RedirectResponse
    {
        if ($user->id === 1) {
            return back()->with('error', 'El administrador principal (ID 1) no se puede eliminar.');
        }

        if ($user->id === auth()->id()) {
            return back()->with('error', 'No puedes eliminar tu propia cuenta en uso.');
        }

        $name = $user->name;

        if ($this->tieneHistorialDeProduccion($user)) {
            $this->anonimizarUsuario($user);

            return back()->with('success', "Los datos de '{$name}' fueron eliminados. Su historial de producción se conserva, y su lugar en las órdenes/subórdenes ya está disponible para reasignarlo a otro operario.");
        }

        $user->roles()->detach();
        $user->permissions()->detach();
        $user->delete();

        return back()->with('success', "Usuario '{$name}' eliminado correctamente.");
    }

    public function updateRole(Request $request, User $user): RedirectResponse
    {
        $request->validate(['role_id' => 'required|exists:roles,id']);
        $user->roles()->sync([$request->role_id]);

        return back()->with('success', "Rol de '{$user->name}' actualizado.");
    }
}