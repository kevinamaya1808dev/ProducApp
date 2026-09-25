<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
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

        $module = $special ? null : Str::slug($input['module'], '_');
        $slug = $special ? $input['slug'] : "{$module}.{$input['action']}";

        if (Permission::where('slug', $slug)->when($existing, fn ($q) => $q->where('id', '!=', $existing->id))->exists()) {
            throw ValidationException::withMessages(['slug' => "Ya existe un permiso con el slug '{$slug}'."]);
        }

        return [
            'name'       => $input['name'],
            'slug'       => $slug,
            'module'     => $module,
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

    public function create(Request $request): View
    {
        return view('admin.permissions.create', [
            'actions'        => self::ACTIONS,
            'prefillModule'  => $request->query('module'),
            'prefillAction'  => $request->query('action'),
            'moduleOptions'  => Permission::where('is_special', false)->pluck('module')->unique()->sort()->values(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        // Permiso especial: sigue siendo uno a la vez, con nombre y slug libres.
        if ($request->boolean('is_special')) {
            $data = $request->validate([
                'name' => 'required|string|max:255',
                'slug' => 'required|string|max:150|unique:permissions,slug',
            ]);

            Permission::create([
                'name'       => $data['name'],
                'slug'       => $data['slug'],
                'module'     => null,
                'action'     => null,
                'is_special' => true,
            ]);

            return redirect()->route('admin.permissions.index')->with('success', "Permiso especial '{$data['name']}' creado correctamente.");
        }

        // Permiso de módulo: se pueden crear varias acciones de un mismo módulo
        // en un solo envío, en vez de repetir el formulario una vez por acción.
        $data = $request->validate([
            'module'    => 'required|string|max:100',
            'actions'   => 'required|array|min:1',
            'actions.*' => ['required', 'distinct', 'in:' . implode(',', array_keys(self::ACTIONS))],
            'names'     => 'nullable|array',
            'names.*'   => 'nullable|string|max:255',
        ]);

        $module = Str::slug($data['module'], '_');
        $moduleLabel = Str::headline($module);

        $created = 0;
        $skipped = 0;

        DB::transaction(function () use ($data, $module, $moduleLabel, &$created, &$skipped) {
            foreach ($data['actions'] as $action) {
                $slug = "{$module}.{$action}";

                // Si ya existe (ej. estabas completando un módulo parcial), se omite
                // en vez de tronar por slug duplicado.
                if (Permission::where('slug', $slug)->exists()) {
                    $skipped++;
                    continue;
                }

                $name = trim($data['names'][$action] ?? '') ?: (self::ACTIONS[$action] . ' ' . $moduleLabel);

                Permission::create([
                    'name'       => $name,
                    'slug'       => $slug,
                    'module'     => $module,
                    'action'     => $action,
                    'is_special' => false,
                ]);

                $created++;
            }
        });

        if ($created === 0) {
            return back()->withInput()->with('error', "Los permisos seleccionados para '{$moduleLabel}' ya existían, no se creó ninguno nuevo.");
        }

        $message = $created === 1
            ? "Se creó 1 permiso para '{$moduleLabel}'."
            : "Se crearon {$created} permisos para '{$moduleLabel}'.";

        if ($skipped > 0) {
            $message .= " ({$skipped} ya existían y se omitieron.)";
        }

        return redirect()->route('admin.permissions.index')->with('success', $message);
    }

    public function edit(Permission $permission): View
    {
        return view('admin.permissions.edit', [
            'permission'    => $permission,
            'actions'       => self::ACTIONS,
            'moduleOptions' => Permission::where('is_special', false)->pluck('module')->unique()->sort()->values(),
        ]);
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