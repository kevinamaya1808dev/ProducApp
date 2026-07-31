<?php

namespace App\Http\Controllers;

use App\Models\Component;
use App\Models\Category;
use Illuminate\Http\Request;

class ComponentController extends Controller
{
    public function index()
    {
        $components = Component::with(['category', 'componentType'])->orderBy('name')->get();
        $categories = Category::orderBy('name')->get();

        return view('components.index', compact('components', 'categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'nullable|exists:categories,id',
            'name'        => 'required|string|max:255',
            'sku'         => 'nullable|string|unique:components,sku',
            'base_unit'   => 'required|string|max:20',
        ]);

        Component::create($validated);

        return back()->with('success', 'Componente registrado correctamente.');
    }

    public function update(Request $request, Component $component)
    {
        $validated = $request->validate([
            'category_id' => 'nullable|exists:categories,id',
            'name'        => 'required|string|max:255',
            'sku'         => 'nullable|string|unique:components,sku,' . $component->id,
            'base_unit'   => 'required|string|max:20',
        ]);

        $component->update($validated);

        return back()->with('success', 'Componente actualizado correctamente.');
    }

    public function destroy(Component $component)
    {
        if ($component->recipes()->exists()) {
            return back()->with('error', 'No puedes eliminar un componente que está usado en recetas. Quítalo de esas recetas primero.');
        }

        $component->delete();

        return back()->with('success', 'Componente eliminado correctamente.');
    }
}