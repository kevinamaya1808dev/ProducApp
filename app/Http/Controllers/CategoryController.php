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
            $query->where('name', 'like', '%' . $request->search . '%');
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
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        $category = Category::create($validated);

        return redirect()->route('admin.categories.index', ['category' => $category->id])
                         ->with('success', 'Categoría creada correctamente.');
    }

    public function update(Request $request, Category $category): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        $category->update($validated);

        return redirect()->route('admin.categories.index', ['category' => $category->id])
                         ->with('success', 'Categoría actualizada correctamente.');
    }

    public function destroy(Category $category): RedirectResponse
    {
        $category->delete();

        return redirect()->route('admin.categories.index')
                         ->with('success', 'Categoría eliminada correctamente.');
    }
}