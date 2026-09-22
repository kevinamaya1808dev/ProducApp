<?php

namespace App\Http\Controllers;

use App\Models\ComponentType;
use Illuminate\Http\Request;

class ComponentTypeController extends Controller
{
    private function rules(?int $ignoreId = null): array
    {
        return [
            'name'  => 'required|string|max:100|unique:component_types,name' . ($ignoreId ? ",$ignoreId" : ''),
            'color' => 'required|string|in:' . implode(',', array_keys(ComponentType::colorPalette())),
        ];
    }

    public function index()
    {
        return view('admin.component-types.index', [
            'componentTypes' => ComponentType::withCount('components')->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        ComponentType::create($request->validate($this->rules()));

        return redirect()->route('admin.component-types.index')->with('success', 'Tipo de componente creado.');
    }

    public function update(Request $request, ComponentType $componentType)
    {
        $componentType->update($request->validate($this->rules($componentType->id)));

        return redirect()->route('admin.component-types.index')->with('success', 'Tipo de componente actualizado.');
    }

    public function destroy(ComponentType $componentType)
    {
        if ($componentType->components()->exists()) {
            return redirect()->route('admin.component-types.index')
                ->with('error', 'No puedes eliminar un tipo con componentes asignados. Reasigna esos componentes primero.');
        }

        $componentType->delete();

        return redirect()->route('admin.component-types.index')->with('success', 'Tipo de componente eliminado.');
    }
}