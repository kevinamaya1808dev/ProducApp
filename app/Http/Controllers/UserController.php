<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index(): View
    {
        $totalUsers = User::count();
        $users = User::with(['roles', 'permissions', 'productionOrders'])->get();
        $roles = Role::all();

        return view('admin.users.index', compact('users', 'roles', 'totalUsers'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'email'       => 'required|email|unique:users,email',
            'password'    => 'required|string|min:8',
            'role_id'     => 'required|exists:roles,id',
            'active'      => 'required|boolean',
            'puesto'      => 'nullable|string|max:100',
            'turno'       => 'nullable|string|max:50',
            'estacion'    => 'nullable|string|max:100',
            'meta_diaria' => 'nullable|integer|min:0',
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
        ]);

        $user->roles()->sync([$validated['role_id']]);

        return redirect()->route('admin.users.permissions.edit', $user)
                         ->with('success', "Operario '{$user->name}' creado exitosamente. Ahora puedes asignarle permisos.");
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'email'       => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'password'    => 'nullable|string|min:8',
            'role_id'     => 'required|exists:roles,id',
            'active'      => 'required|boolean',
            'puesto'      => 'nullable|string|max:100',
            'turno'       => 'nullable|string|max:50',
            'estacion'    => 'nullable|string|max:100',
            'meta_diaria' => 'nullable|integer|min:0',
        ]);

        $user->name        = $validated['name'];
        $user->email       = $validated['email'];
        $user->puesto      = $validated['puesto'] ?? null;
        $user->turno       = $validated['turno'] ?? null;
        $user->estacion    = $validated['estacion'] ?? null;
        $user->active      = $validated['active'];
        $user->meta_diaria = $validated['meta_diaria'] ?? null;

        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        $user->roles()->sync([$validated['role_id']]);

        return back()->with('success', "Usuario '{$user->name}' actualizado correctamente.");
    }

    public function editPermissions(User $user): View
    {
        $permissions = Permission::orderBy('module')->orderBy('action')->get();

        $permissionModules = $permissions->where('is_special', false)->groupBy('module');
        $specialPermissions = $permissions->where('is_special', true);
        $selectedIds = $user->permissions->pluck('id')->toArray();

        return view('admin.users.permissions', compact('user', 'permissionModules', 'specialPermissions', 'selectedIds'));
    }

    public function updatePermissions(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'permissions'   => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        $user->permissions()->sync($validated['permissions'] ?? []);

        return redirect()->route('admin.users.index')
                         ->with('success', "Permisos de '{$user->name}' actualizados correctamente.");
    }

    /**
     * Indica si el usuario tiene algún rastro en la línea productiva:
     * órdenes de las que es encargado, registros de producción propios,
     * incidencias reportadas por él, o asignaciones a subórdenes (tabla
     * pivote production_sub_order_user).
     *
     * NUEVO: se usa en destroy() para decidir si es seguro borrar la fila
     * físicamente o si hay que anonimizar en su lugar (ver destroy()).
     */
    private function tieneHistorialDeProduccion(User $user): bool
    {
        if ($user->productionOrders()->exists()) {
            return true;
        }

        if ($user->registrosProduccion()->exists()) {
            return true;
        }

        if ($user->incidences()->exists()) {
            return true;
        }

        // El modelo User no tiene una relación inversa hacia
        // production_sub_order_user (solo ProductionSubOrder::assignedUsers()
        // apunta hacia allá), así que se consulta la tabla pivote directo.
        return DB::table('production_sub_order_user')->where('user_id', $user->id)->exists();
    }

    /**
     * Borra los datos de identificación del usuario (nombre, correo,
     * contraseña, puesto, turno, estación, notas) y lo desactiva, pero
     * conserva la fila en `users` con el mismo ID.
     *
     * NUEVO: esto es lo que reemplaza a un DELETE físico cuando el usuario
     * tiene historial de producción. production_orders.user_id,
     * registro_produccions.user_id e incidences.user_id están definidos
     * como NOT NULL con onDelete('cascade') — un DELETE real sobre este
     * usuario borraría en cascada todas esas órdenes/registros/incidencias,
     * incluidos los de OTROS operarios que colaboraron en las mismas
     * órdenes. Al conservar la fila (con un ID válido pero sin datos
     * identificables), ninguna FK se dispara y el historial queda intacto.
     * El "puesto" que ocupaba en cada orden/suborden queda disponible para
     * reasignarse a otro operario desde la edición de la orden (el campo
     * "encargado") o de la suborden (el listado de operarios asignados).
     */
    private function anonimizarUsuario(User $user): void
    {
        $user->name        = 'Usuario eliminado #' . $user->id;
        $user->email       = 'usuario-eliminado-' . $user->id . '@baja.local';
        // Contraseña aleatoria e inaccesible: ni el propio usuario ni nadie
        // más puede volver a iniciar sesión con esta cuenta.
        $user->password    = Hash::make(Str::random(40));
        $user->puesto      = null;
        $user->turno       = null;
        $user->estacion    = null;
        $user->notas       = null;
        $user->meta_diaria = null;
        $user->active      = false;
        $user->save();

        // Se le retiran roles y permisos directos: la cuenta queda sin
        // ningún acceso al sistema, aunque su ID se conserve.
        $user->roles()->detach();
        $user->permissions()->detach();
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

        // CORRECCIÓN: antes esto era siempre un $user->delete() directo. Como
        // production_orders.user_id (y en cascada sus subórdenes, registros
        // de producción e incidencias) usa onDelete('cascade'), borrar a un
        // operario con historial de producción borraba silenciosamente TODA
        // esa producción — incluida la de otros operarios que participaron
        // en las mismas órdenes. Ahora: si el usuario tiene historial, se
        // anonimiza en vez de borrarse (ver anonimizarUsuario()); solo se
        // hace DELETE físico cuando nunca participó en producción, caso en
        // el que no hay nada que proteger.
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
        $request->validate([
            'role_id' => 'required|exists:roles,id',
        ]);

        $user->roles()->sync([$request->role_id]);

        return back()->with('success', "Rol de '{$user->name}' actualizado.");
    }
}