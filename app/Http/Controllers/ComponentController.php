<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class CategoryController extends Controller
{
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
        $category = Category::create(
            $request->validate(['name' => 'required|string|max:255|unique:categories,name'])
        );

        return redirect()->route('admin.categories.index', ['category' => $category->id])
            ->with('success', 'Categoría creada correctamente.');
    }

    public function update(Request $request, Category $category): RedirectResponse
    {
        $category->update(
            $request->validate(['name' => "required|string|max:255|unique:categories,name,{$category->id}"])
        );

        return redirect()->route('admin.categories.index', ['category' => $category->id])
            ->with('success', 'Categoría actualizada correctamente.');
    }

    public function destroy(Category $category): RedirectResponse
    {
        // Borrar una categoría con productos eliminaba en cascada sus productos,
        // órdenes, subórdenes, registros e incidencias (y con componentes
        // asociados la BD lanzaba un error 500). Ahora se bloquea.
        $productos   = $category->products()->count();
        $componentes = DB::table('components')->where('category_id', $category->id)->count();

        if ($productos > 0 || $componentes > 0) {
            $detalle = collect([
                $productos   ? "{$productos} producto(s)"   : null,
                $componentes ? "{$componentes} componente(s)" : null,
            ])->filter()->implode(' y ');

            return redirect()->route('admin.categories.index')->with('error',
                "No se puede eliminar la categoría '{$category->name}': tiene {$detalle} asociado(s). Reasígnalos o elimínalos primero.");
        }

        $category->delete();

        return redirect()->route('admin.categories.index')->with('success', 'Categoría eliminada correctamente.');
    }
}