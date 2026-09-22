<?php

namespace App\Http\Controllers;

use App\Models\ComponentType;
use App\Models\Product;
use App\Models\Recipe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RecipeController extends Controller
{
    private function rules(bool $requireInstructions = false): array
    {
        return [
            'product_id'   => 'required|exists:products,id',
            'name'         => 'required|string|max:255',
            'instructions' => ($requireInstructions ? 'required' : 'nullable') . '|string',
        ];
    }

    private function resolveActive(Request $request, $recipes): ?Recipe
    {
        if ($recipes->isEmpty()) return null;

        $id = $request->input('recipe', $recipes->first()->id);

        return Recipe::with(['product', 'components.componentType'])->find($id)
            ?? Recipe::with(['product', 'components.componentType'])->find($recipes->first()->id);
    }

    public function index(Request $request)
    {
        $recipes = Recipe::with('product')
            ->when($request->search, fn ($q, $v) =>
                $q->where(fn ($q) =>
                    $q->where('name', 'like', "%{$v}%")->orWhere('instructions', 'like', "%{$v}%")
                )
            )
            ->latest()->get();

        return view('admin.recetas.index', [
            'recipes'        => $recipes,
            'activeRecipe'   => $this->resolveActive($request, $recipes),
            'products'       => Product::orderBy('name')->get(),
            'componentTypes' => ComponentType::orderBy('name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $recipe = Recipe::create($request->validate($this->rules(true)));

        return redirect()->route('admin.recipes.index', ['recipe' => $recipe->id])
            ->with('success', 'Receta creada exitosamente.');
    }

    public function update(Request $request, Recipe $recipe)
    {
        $recipe->update($request->validate($this->rules()));

        return redirect()->route('admin.recipes.index', ['recipe' => $recipe->id])
            ->with('success', 'Receta actualizada exitosamente.');
    }

    public function destroy(Recipe $recipe)
    {
        $recipe->delete();

        return redirect()->route('admin.recipes.index')->with('success', 'Receta eliminada correctamente.');
    }

    public function duplicate(Recipe $recipe)
    {
        $new = DB::transaction(function () use ($recipe) {
            $copy = $recipe->replicate();
            $copy->name = $recipe->name . ' (Copia)';
            $copy->save();

            $copy->components()->sync(
                $recipe->components->mapWithKeys(fn ($c) => [$c->id => ['quantity' => $c->pivot->quantity]])->toArray()
            );

            return $copy;
        });

        return redirect()->route('admin.recipes.index', ['recipe' => $new->id])
            ->with('success', 'Receta duplicada exitosamente.');
    }
}