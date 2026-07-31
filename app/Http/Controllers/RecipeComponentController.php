<?php

namespace App\Http\Controllers;

use App\Models\Recipe;
use App\Models\Component;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RecipeComponentController extends Controller
{
    public function store(Request $request, Recipe $recipe)
    {
        $validated = $request->validate([
            'name'               => 'required|string|max:255',
            'component_type_id'  => 'nullable|exists:component_types,id',
            'base_unit'          => 'required|string|max:20',
            'quantity'           => 'required|numeric|min:0.01',
        ]);

        DB::transaction(function () use ($validated, $recipe) {
            $component = Component::firstOrCreate(
                ['name' => $validated['name']],
                [
                    'component_type_id' => $validated['component_type_id'] ?? null,
                    'base_unit'         => $validated['base_unit'],
                ]
            );

            $component->update([
                'component_type_id' => $validated['component_type_id'] ?? $component->component_type_id,
                'base_unit'         => $validated['base_unit'],
            ]);

            $recipe->components()->syncWithoutDetaching([
                $component->id => ['quantity' => $validated['quantity']],
            ]);
        });

        return redirect()->route('recipes.index', ['recipe' => $recipe->id])
                         ->with('success', 'Componente agregado a la receta.');
    }

    public function update(Request $request, Recipe $recipe, Component $component)
    {
        $validated = $request->validate([
            'name'               => 'required|string|max:255',
            'component_type_id'  => 'nullable|exists:component_types,id',
            'base_unit'          => 'required|string|max:20',
            'quantity'           => 'required|numeric|min:0.01',
        ]);

        DB::transaction(function () use ($validated, $recipe, $component) {
            $component->update([
                'name'               => $validated['name'],
                'component_type_id'  => $validated['component_type_id'] ?? null,
                'base_unit'          => $validated['base_unit'],
            ]);

            $recipe->components()->updateExistingPivot($component->id, [
                'quantity' => $validated['quantity'],
            ]);
        });

        return redirect()->route('recipes.index', ['recipe' => $recipe->id])
                         ->with('success', 'Componente actualizado correctamente.');
    }

    public function destroy(Recipe $recipe, Component $component)
    {
        $recipe->components()->detach($component->id);

        return redirect()->route('recipes.index', ['recipe' => $recipe->id])
                         ->with('success', 'Componente removido de la receta.');
    }
}