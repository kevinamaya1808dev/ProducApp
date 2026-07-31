<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index(Request $request): View
    {
        $query = Category::query();
        
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
        }

        $categories = $query->latest()->paginate(10);

        // 1. Capturar la categoría activa seleccionada por el usuario
        $activeCategory = null;
        if ($request->filled('category')) {
            $activeCategory = Category::find($request->category);
        }
        
        // 2. Si no hay una seleccionada pero existen categorías, seleccionamos la primera por defecto
        if (!$activeCategory && $categories->isNotEmpty()) {
            $activeCategory = $categories->first();
        }

        return view('admin.categories.index', compact('categories', 'activeCategory'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'description' => 'nullable|string',
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        $category = Category::create($validated);

        // Redirigir seleccionando automáticamente la categoría recién creada
        return redirect()->route('categories.index', ['category' => $category->id])
                         ->with('success', 'Categoría creada correctamente.');
    }

    public function update(Request $request, Category $category): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
            'description' => 'nullable|string',
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        $category->update($validated);

        // Redirigir manteniendo seleccionada la categoría actualizada
        return redirect()->route('categories.index', ['category' => $category->id])
                         ->with('success', 'Categoría actualizada correctamente.');
    }

    public function destroy(Category $category): RedirectResponse
    {
        $category->delete();

        return redirect()->route('categories.index')
                         ->with('success', 'Categoría eliminada correctamente.');
    }
}