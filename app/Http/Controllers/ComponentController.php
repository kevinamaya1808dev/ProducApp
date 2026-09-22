<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Component;
use Illuminate\Http\Request;

class ComponentController extends Controller
{
    private function rules(?int $ignoreId = null): array
    {
        return [
            'category_id' => 'nullable|exists:categories,id',
            'name'        => 'required|string|max:255',
            'sku'         => 'nullable|string|unique:components,sku' . ($ignoreId ? ",$ignoreId" : ''),
            'base_unit'   => 'required|string|max:20',
        ];
    }

    public function index()
    {
        return view('components.index', [
            'components' => Component::with(['category', 'componentType'])->orderBy('name')->get(),
            'categories' => Category::orderBy('name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        Component::create($request->validate($this->rules()));

        return back()->with('success', 'Componente registrado correctamente.');
    }

    public function update(Request $request, Component $component)
    {
        $component->update($request->validate($this->rules($component->id)));

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