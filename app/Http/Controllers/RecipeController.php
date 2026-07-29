<?php

namespace App\Http\Controllers;

use App\Models\Recipe;
use App\Models\Product;
use App\Models\Component;
use Illuminate\Http\Request;

class RecipeController extends Controller
{
    // Muestra la vista principal Maestro-Detalle
    public function index(Request $request)
    {
        // Buscador opcional en el panel izquierdo
        $search = $request->input('search');

        $recipes = Recipe::with(['product', 'components.category'])
            ->when($search, function ($query, $search) {
                $query->whereHas('product', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('internal_code', 'like', "%{$search}%");
                })->orWhere('version', 'like', "%{$search}%");
            })
            ->get();

        // Receta activa por defecto (la primera o la seleccionada)
        $activeRecipeId = $request->input('recipe', $recipes->first()?->id);
        $activeRecipe = $recipes->firstWhere('id', $activeRecipeId) ?? $recipes->first();

        // Catálogo de componentes para el modal de creación/edición
        $allComponents = Component::all();
        $products = Product::all();

        return view('admin.recetas.index', compact('recipes', 'activeRecipe', 'allComponents', 'products', 'search'));
    }

    // Guardar una nueva receta
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'version' => 'required|string|max:50',
            'components' => 'required|array|min:1',
            'components.*.id' => 'required|exists:components,id',
            'components.*.quantity' => 'required|numeric|min:0.01',
        ]);

        $recipe = Recipe::create([
            'product_id' => $request->product_id,
            'version' => $request->version,
            'is_active' => true,
        ]);

        // Sincronizar componentes con sus cantidades en la tabla pivote
        $syncData = [];
        foreach ($request->components as $comp) {
            $syncData[$comp['id']] = ['quantity' => $comp['quantity']];
        }
        $recipe->components()->sync($syncData);

        return redirect()->route('recipes.index')->with('success', 'Receta creada exitosamente.');
    }

    // Actualizar receta existente
    public function update(Request $request, Recipe $recipe)
    {
        $request->validate([
            'version' => 'required|string|max:50',
            'components' => 'required|array|min:1',
        ]);

        $recipe->update([
            'version' => $request->version,
        ]);

        $syncData = [];
        foreach ($request->components as $comp) {
            $syncData[$comp['id']] = ['quantity' => $comp['quantity']];
        }
        $recipe->components()->sync($syncData);

        return redirect()->route('recipes.index')->with('success', 'Receta actualizada correctamente.');
    }
}