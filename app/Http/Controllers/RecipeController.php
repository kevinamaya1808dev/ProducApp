<?php

namespace App\Http\Controllers;

use App\Models\Recipe;
use App\Models\Product;
use App\Models\ComponentType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RecipeController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $recipes = Recipe::with('product')
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('instructions', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->get();

        $activeRecipe = null;
        if ($recipes->isNotEmpty()) {
            $recipeId = $request->input('recipe');

            if ($recipeId) {
                $activeRecipe = Recipe::with(['product', 'components.componentType'])->find($recipeId);
            }

            if (!$activeRecipe) {
                $activeRecipe = Recipe::with(['product', 'components.componentType'])->find($recipes->first()->id);
            }
        }

        $products = Product::orderBy('name')->get();
        $componentTypes = ComponentType::orderBy('name')->get();

        return view('admin.recetas.index', compact('recipes', 'activeRecipe', 'products', 'componentTypes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id'   => 'required|exists:products,id',
            'name'         => 'required|string|max:255',
            'instructions' => 'required|string',
        ]);

        $recipe = Recipe::create($validated);

        return redirect()->route('recipes.index', ['recipe' => $recipe->id])
                         ->with('success', 'Receta creada exitosamente.');
    }

    public function update(Request $request, Recipe $recipe)
    {
        $validated = $request->validate([
            'product_id'   => 'required|exists:products,id',
            'name'         => 'required|string|max:255',
            'instructions' => 'nullable|string',
        ]);

        $recipe->update($validated);

        return redirect()->route('recipes.index', ['recipe' => $recipe->id])
                         ->with('success', 'Receta actualizada exitosamente.');
    }

    public function destroy(Recipe $recipe)
    {
        $recipe->delete();

        return redirect()->route('recipes.index')
                         ->with('success', 'Receta eliminada correctamente.');
    }

    public function duplicate(Recipe $recipe)
    {
        $newRecipe = DB::transaction(function () use ($recipe) {
            $copy = $recipe->replicate();
            $copy->name = $recipe->name . ' (Copia)';
            $copy->save();

            $pivotData = $recipe->components->mapWithKeys(function ($component) {
                return [$component->id => ['quantity' => $component->pivot->quantity]];
            })->toArray();

            $copy->components()->sync($pivotData);

            return $copy;
        });

        return redirect()->route('recipes.index', ['recipe' => $newRecipe->id])
                         ->with('success', 'Receta duplicada exitosamente.');
    }
}