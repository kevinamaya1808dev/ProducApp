<?php

namespace App\Http\Controllers;

use App\Models\Recipe;
use App\Models\Product;
use App\Models\Role; // <--- Añade esta línea
use Illuminate\Http\Request;

class RecipeController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        
        $recipes = Recipe::with('product')
            ->when($search, function ($query, $search) {
                return $query->where('name', 'like', "%{$search}%")
                           ->orWhere('instructions', 'like', "%{$search}%");
            })
            ->latest()
            ->get();

        $activeRecipe = null;
        if ($recipes->isNotEmpty()) {
            $recipeId = $request->input('recipe');
            
            if ($recipeId) {
                $activeRecipe = Recipe::with(['product', 'components'])->find($recipeId);
            }
            
            if (!$activeRecipe) {
                $activeRecipe = Recipe::with(['product', 'components'])->find($recipes->first()->id);
            }
        }

        $products = Product::all();
        $roles = Role::all(); // <--- Añade esta línea para obtener los roles

        // Añade 'roles' dentro de compact():
        return view('admin.recetas.index', compact('recipes', 'activeRecipe', 'products', 'roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'name' => 'required|string|max:255',
            'instructions' => 'nullable|string',
        ]);

        $recipe = Recipe::create([
            'product_id' => $request->product_id,
            'name' => $request->name,
            'instructions' => $request->instructions,
        ]);

        return redirect()->route('recipes.index', ['recipe' => $recipe->id])
                         ->with('success', 'Receta creada exitosamente.');
    }

    public function update(Request $request, Recipe $recipe)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'name' => 'required|string|max:255',
            'instructions' => 'nullable|string',
        ]);

        $recipe->update([
            'product_id' => $request->product_id,
            'name' => $request->name,
            'instructions' => $request->instructions,
        ]);

        return redirect()->route('recetas.index', ['recipe' => $recipe->id])
                         ->with('success', 'Receta actualizada exitosamente.');
    }

    public function destroy(Recipe $recipe)
    {
        $recipe->delete();

        return redirect()->route('recipes.index')
                         ->with('success', 'Receta eliminada correctamente.');
    }
}