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
    // Cuenta del administrador principal: INTOCABLE. Nadie (ni otros admins, ni
    // ella misma) puede editarla, cambiarle clave/rol/permisos, darla de baja
    // o eliminarla desde la aplicación.
    private const ADMIN_PRINCIPAL_ID = 1;

    private const MENSAJE_INTOCABLE = 'La cuenta del administrador principal es intocable: no se puede editar, dar de baja, eliminar ni cambiar su contraseña, rol o permisos.';

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

    // ==========================================
    // CANDADOS DE JERARQUÍA
    // Un usuario sin rol de administrador (aunque tenga users.edit /
    // users.delete) no puede escalar privilegios ni tocar cuentas admin.
    // ==========================================
    private function actorEsAdmin(): bool
    {
        return auth()->user()->hasRole('admin');
    }

    private function esCuentaIntocable(User $user): bool
    {
        return $user->exists && (int) $user->id === self::ADMIN_PRINCIPAL_ID;
    }

    private function esElMismo(User $user): bool
    {
        return (int) $user->id === (int) auth()->id();
    }

    private function esRolAdmin(int $roleId): bool
    {
        return Role::whereKey($roleId)->where('slug', 'admin')->exists();
    }

    private function yaTieneElRol(User $user, int $roleId): bool
    {
        return $user->roles()->where('roles.id', $roleId)->exists();
    }

    /**
     * Devuelve el mensaje de error si el actor NO puede gestionar a $user o
     * asignar $roleId; null si está permitido. Los administradores pasan siempre.
     */
    private function bloqueoDeJerarquia(User $user, ?int $roleId = null): ?string
    {
        if ($this->actorEsAdmin()) {
            return null;
        }

        if ($user->exists && $user->hasRole('admin')) {
            return 'Solo un administrador puede modificar la cuenta de otro administrador.';
        }

        if ($roleId !== null && $this->esRolAdmin($roleId)) {
            return 'Solo un administrador puede asignar el rol de administrador.';
        }

        return null;
    }

    /** IDs de permisos que el actor posee (directos + los de sus roles). */
    private function permisosDelActor(): array
    {
        $actor   = auth()->user();
        $roleIds = $actor->roles()->pluck('roles.id');

        return $actor->permissions()->pluck('permissions.id')
            ->merge(DB::table('permission_role')->whereIn('role_id', $roleIds)->pluck('permission_id'))
            ->map(fn ($id) => (int) $id)
            ->unique()
            ->values()
            ->all();
    }

    // ==========================================
    // BORRADO / BAJA
    // ==========================================
    private function tieneHistorialDeProduccion(User $user): bool
    {
        return $user->productionOrders()->exists()
            || $user->registrosProduccion()->exists()
            || $user->incidences()->exists()
            || DB::table('incidence_logs')->where('user_id', $user->id)->exists();
    }

    private function anonimizarUsuario(User $user): void
    {
        // Nota: la tabla users NO tiene columna "notas" (antes se intentaba
        // limpiarla y el borrado de cualquier usuario con historial fallaba).
        $user->forceFill([
            'name'        => 'Usuario eliminado #' . $user->id,
            'email'       => 'usuario-eliminado-' . $user->id . '@baja.local',
            'password'    => Hash::make(Str::random(40)),
            'puesto'      => null,
            'turno'       => null,
            'estacion'    => null,
            'meta_diaria' => null,
            'active'      => false,
        ])->save();

        $user->roles()->detach();
        $user->permissions()->detach();
    }

    // ==========================================
    // ACCIONES
    // ==========================================
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

        if ($mensaje = $this->bloqueoDeJerarquia(new User, (int) $data['role_id'])) {
            return back()->with('error', $mensaje);
        }

        $user = $this->fillUser(new User, $data);
        $user->save();
        $user->roles()->sync([$data['role_id']]);

        return redirect()->route('admin.users.index')
            ->with('success', "Operario '{$user->name}' creado exitosamente.");
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        if ($this->esCuentaIntocable($user)) {
            return back()->with('error', self::MENSAJE_INTOCABLE);
        }

        $data   = $request->validate($this->userRules($user->id));
        $roleId = (int) $data['role_id'];

        if ($mensaje = $this->bloqueoDeJerarquia($user, $roleId)) {
            return back()->with('error', $mensaje);
        }

        if ($this->esElMismo($user)) {
            if (!filter_var($data['active'], FILTER_VALIDATE_BOOLEAN)) {
                return back()->with('error', 'No puedes darte de baja a ti mismo.');
            }

            if (!$this->yaTieneElRol($user, $roleId)) {
                return back()->with('error', 'No puedes cambiar tu propio rol.');
            }
        }

        $this->fillUser($user, $data)->save();
        $user->roles()->sync([$roleId]);

        return back()->with('success', "Usuario '{$user->name}' actualizado correctamente.");
    }

    public function editPermissions(User $user): View
    {
        abort_if($this->esCuentaIntocable($user), 403, self::MENSAJE_INTOCABLE);
        abort_if($this->bloqueoDeJerarquia($user) !== null, 403);

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
        if ($this->esCuentaIntocable($user)) {
            return back()->with('error', self::MENSAJE_INTOCABLE);
        }

        $data = $request->validate([
            'permissions'   => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        if ($mensaje = $this->bloqueoDeJerarquia($user)) {
            return back()->with('error', $mensaje);
        }

        $solicitados = collect($data['permissions'] ?? [])->map(fn ($id) => (int) $id)->unique();

        if ($this->actorEsAdmin()) {
            $user->permissions()->sync($solicitados->all());
        } else {
            if ($this->esElMismo($user)) {
                return back()->with('error', 'No puedes modificar tus propios permisos.');
            }

            // Un no-admin solo puede conceder/quitar permisos que él mismo posee;
            // los que no puede gestionar se conservan tal como estaban.
            $gestionables = collect($this->permisosDelActor());
            $actuales     = $user->permissions()->pluck('permissions.id')->map(fn ($id) => (int) $id);

            $user->permissions()->sync(
                $actuales->diff($gestionables)
                    ->merge($solicitados->intersect($gestionables))
                    ->unique()
                    ->values()
                    ->all()
            );
        }

        return redirect()->route('admin.users.index')
            ->with('success', "Permisos de '{$user->name}' actualizados correctamente.");
    }

    public function destroy(User $user): RedirectResponse
    {
        if ($this->esCuentaIntocable($user)) {
            return back()->with('error', self::MENSAJE_INTOCABLE);
        }

        if ($this->esElMismo($user)) {
            return back()->with('error', 'No puedes eliminar tu propia cuenta en uso.');
        }

        if ($mensaje = $this->bloqueoDeJerarquia($user)) {
            return back()->with('error', $mensaje);
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
        if ($this->esCuentaIntocable($user)) {
            return back()->with('error', self::MENSAJE_INTOCABLE);
        }

        $request->validate(['role_id' => 'required|exists:roles,id']);
        $roleId = (int) $request->role_id;

        if ($mensaje = $this->bloqueoDeJerarquia($user, $roleId)) {
            return back()->with('error', $mensaje);
        }

        if ($this->esElMismo($user) && !$this->yaTieneElRol($user, $roleId)) {
            return back()->with('error', 'No puedes cambiar tu propio rol.');
        }

        $user->roles()->sync([$roleId]);

        return back()->with('success', "Rol de '{$user->name}' actualizado.");
    }
}