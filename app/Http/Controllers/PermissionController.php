<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class PermissionController extends Controller
{
    private const ACTIONS = [
        'view'   => 'Ver',
        'create' => 'Crear',
        'edit'   => 'Editar',
        'delete' => 'Borrar',
        'manage' => 'Gestionar',
    ];

    private function buildAndValidate(Request $request, ?Permission $existing = null): array
    {
        $special = $request->boolean('is_special');

        $input = $request->validate([
            'name'   => 'required|string|max:255',
            'module' => $special ? 'nullable|string|max:100' : 'required|string|max:100',
            'action' => $special ? 'nullable|string|max:50'  : ['required', 'in:' . implode(',', array_keys(self::ACTIONS))],
            'slug'   => $special ? 'required|string|max:150' : 'nullable|string|max:150',
        ]);

        $slug = $special ? $input['slug'] : "{$input['module']}.{$input['action']}";

        if (Permission::where('slug', $slug)->when($existing, fn ($q) => $q->where('id', '!=', $existing->id))->exists()) {
            throw ValidationException::withMessages(['slug' => "Ya existe un permiso con el slug '{$slug}'."]);
        }

        return [
            'name'       => $input['name'],
            'slug'       => $slug,
            'module'     => $special ? null : $input['module'],
            'action'     => $special ? null : $input['action'],
            'is_special' => $special,
        ];
    }

    public function index(): View
    {
        $permissions = Permission::orderBy('module')->orderBy('action')->get();

        return view('admin.permissions.index', [
            'modules' => $permissions->where('is_special', false)->groupBy('module'),
            'special' => $permissions->where('is_special', true),
            'actions' => self::ACTIONS,
        ]);
    }

    public function create(): View
    {
        return view('admin.permissions.create', ['actions' => self::ACTIONS]);
    }

    public function store(Request $request): RedirectResponse
    {
        Permission::create($this->buildAndValidate($request));

        return redirect()->route('admin.permissions.index')->with('success', 'Permiso creado correctamente.');
    }

    public function edit(Permission $permission): View
    {
        return view('admin.permissions.edit', ['permission' => $permission, 'actions' => self::ACTIONS]);
    }

    public function update(Request $request, Permission $permission): RedirectResponse
    {
        $permission->update($this->buildAndValidate($request, $permission));

        return redirect()->route('admin.permissions.index')->with('success', 'Permiso actualizado correctamente.');
    }

    public function destroy(Permission $permission): RedirectResponse
    {
        $permission->delete();

        return redirect()->route('admin.permissions.index')->with('success', 'Permiso eliminado correctamente.');
    }
}