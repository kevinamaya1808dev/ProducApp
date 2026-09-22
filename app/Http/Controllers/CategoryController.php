<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CategoryController extends Controller
{
    private function withSlug(array $data): array
    {
        return $data + ['slug' => Str::slug($data['name'])];
    }

    public function index(Request $request): View
    {
        $categories = Category::query()
            ->when($request->search, fn ($q, $v) => $q->where('name', 'like', "%{$v}%"))
            ->latest()->paginate(10);

        $activeCategory = ($request->filled('category') ? Category::find($request->category) : null)
            ?? $categories->first();

        return view('admin.categories.index', compact('categories', 'activeCategory'));
    }

    public function store(Request $request): RedirectResponse
    {
        $category = Category::create($this->withSlug(
            $request->validate(['name' => 'required|string|max:255|unique:categories,name'])
        ));

        return redirect()->route('admin.categories.index', ['category' => $category->id])
            ->with('success', 'Categoría creada correctamente.');
    }

    public function update(Request $request, Category $category): RedirectResponse
    {
        $category->update($this->withSlug(
            $request->validate(['name' => "required|string|max:255|unique:categories,name,{$category->id}"])
        ));

        return redirect()->route('admin.categories.index', ['category' => $category->id])
            ->with('success', 'Categoría actualizada correctamente.');
    }

    public function destroy(Category $category): RedirectResponse
    {
        $category->delete();

        return redirect()->route('admin.categories.index')->with('success', 'Categoría eliminada correctamente.');
    }
}