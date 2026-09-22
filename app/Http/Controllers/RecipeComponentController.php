<?php

namespace App\Http\Controllers;

use App\Models\Component;
use App\Models\Recipe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RecipeComponentController extends Controller
{
    private function rules(): array
    {
        return [
            'name'              => 'required|string|max:255',
            'component_type_id' => 'nullable|exists:component_types,id',
            'base_unit'         => 'required|string|max:20',
            'quantity'          => 'required|numeric|min:0.01',
        ];
    }

    public function store(Request $request, Recipe $recipe)
    {
        $data = $request->validate($this->rules());

        DB::transaction(function () use ($data, $recipe) {
            $component = Component::firstOrCreate(
                ['name' => $data['name']],
                ['component_type_id' => $data['component_type_id'] ?? null, 'base_unit' => $data['base_unit']]
            );

            // Actualiza el tipo/unidad en caso de que el componente ya existiera
            // con valores distintos a los que llegaron en el formulario.
            $component->update([
                'component_type_id' => $data['component_type_id'] ?? $component->component_type_id,
                'base_unit'         => $data['base_unit'],
            ]);

            $recipe->components()->syncWithoutDetaching([$component->id => ['quantity' => $data['quantity']]]);
        });

        return redirect()->route('admin.recipes.index', ['recipe' => $recipe->id])
            ->with('success', 'Componente agregado a la receta.');
    }

    public function update(Request $request, Recipe $recipe, Component $component)
    {
        $data = $request->validate($this->rules());

        DB::transaction(function () use ($data, $recipe, $component) {
            $component->update([
                'name'              => $data['name'],
                'component_type_id' => $data['component_type_id'] ?? null,
                'base_unit'         => $data['base_unit'],
            ]);

            $recipe->components()->updateExistingPivot($component->id, ['quantity' => $data['quantity']]);
        });

        return redirect()->route('admin.recipes.index', ['recipe' => $recipe->id])
            ->with('success', 'Componente actualizado correctamente.');
    }

    public function destroy(Recipe $recipe, Component $component)
    {
        $recipe->components()->detach($component->id);

        return redirect()->route('admin.recipes.index', ['recipe' => $recipe->id])
            ->with('success', 'Componente removido de la receta.');
    }
}