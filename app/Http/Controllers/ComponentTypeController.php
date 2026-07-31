<?php

namespace App\Http\Controllers;

use App\Models\ComponentType;
use Illuminate\Http\Request;

class ComponentTypeController extends Controller
{
    public function index()
    {
        $componentTypes = ComponentType::withCount('components')->orderBy('name')->get();

        return view('admin.component-types.index', compact('componentTypes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'  => 'required|string|max:100|unique:component_types,name',
            'color' => 'required|string|in:' . implode(',', array_keys(ComponentType::colorPalette())),
        ]);

        ComponentType::create($validated);

        return redirect()->route('component-types.index')->with('success', 'Tipo de componente creado.');
    }

    public function update(Request $request, ComponentType $componentType)
    {
        $validated = $request->validate([
            'name'  => 'required|string|max:100|unique:component_types,name,' . $componentType->id,
            'color' => 'required|string|in:' . implode(',', array_keys(ComponentType::colorPalette())),
        ]);

        $componentType->update($validated);

        return redirect()->route('component-types.index')->with('success', 'Tipo de componente actualizado.');
    }

    public function destroy(ComponentType $componentType)
    {
        if ($componentType->components()->exists()) {
            return redirect()->route('component-types.index')
                             ->with('error', 'No puedes eliminar un tipo con componentes asignados. Reasigna esos componentes primero.');
        }

        $componentType->delete();

        return redirect()->route('component-types.index')->with('success', 'Tipo de componente eliminado.');
    }
}