<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Validation\ValidationException;

class PermissionController extends Controller
{
    private const ACTIONS = [
        'view'   => 'Ver',
        'create' => 'Crear',
        'edit'   => 'Editar',
        'delete' => 'Borrar',
        'manage' => 'Gestionar',
    ];

    public function index(): View
    {
        $permissions = Permission::orderBy('module')->orderBy('action')->get();

        $modules = $permissions->where('is_special', false)->groupBy('module');
        $special = $permissions->where('is_special', true);

        return view('admin.permissions.index', [
            'modules' => $modules,
            'special' => $special,
            'actions' => self::ACTIONS,
        ]);
    }

    public function create(): View
    {
        return view('admin.permissions.create', ['actions' => self::ACTIONS]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->buildAndValidate($request);

        Permission::create($data);

        return redirect()->route('admin.permissions.index')->with('success', 'Permiso creado correctamente.');
    }

    public function edit(Permission $permission): View
    {
        return view('admin.permissions.edit', ['permission' => $permission, 'actions' => self::ACTIONS]);
    }

    public function update(Request $request, Permission $permission): RedirectResponse
    {
        $data = $this->buildAndValidate($request, $permission);

        $permission->update($data);

        return redirect()->route('admin.permissions.index')->with('success', 'Permiso actualizado correctamente.');
    }

    public function destroy(Permission $permission): RedirectResponse
    {
        $permission->delete();

        return redirect()->route('admin.permissions.index')->with('success', 'Permiso eliminado correctamente.');
    }

    private function buildAndValidate(Request $request, ?Permission $permission = null): array
    {
        $isSpecial = $request->boolean('is_special');

        $input = $request->validate([
            'name'   => 'required|string|max:255',
            'module' => $isSpecial ? 'nullable|string|max:100' : 'required|string|max:100',
            'action' => $isSpecial ? 'nullable|string|max:50' : ['required', 'in:' . implode(',', array_keys(self::ACTIONS))],
            'slug'   => $isSpecial ? 'required|string|max:150' : 'nullable|string|max:150',
        ]);

        $slug = $isSpecial ? $input['slug'] : "{$input['module']}.{$input['action']}";

        $slugTaken = Permission::where('slug', $slug)
            ->when($permission, fn ($q) => $q->where('id', '!=', $permission->id))
            ->exists();

        if ($slugTaken) {
            throw ValidationException::withMessages([
                'slug' => "Ya existe un permiso con el slug '{$slug}'.",
            ]);
        }

        return [
            'name'       => $input['name'],
            'slug'       => $slug,
            'module'     => $isSpecial ? null : $input['module'],
            'action'     => $isSpecial ? null : $input['action'],
            'is_special' => $isSpecial,
        ];
    }
}