<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductController extends Controller
{
    private function rules(?int $ignoreId = null): array
    {
        return [
            'category_id' => 'required|exists:categories,id',
            'code'        => 'required|string|max:50|unique:products,code' . ($ignoreId ? ",$ignoreId" : ''),
            'name'        => 'required|string|max:255',
            'stock'       => 'required|integer|min:0',
            'unit_cost'   => 'required|numeric|min:0',
        ];
    }

    public function index(Request $request): View
    {
        $products = Product::with('category')
            ->when($request->search, fn ($q, $v) =>
                $q->where(fn ($q) =>
                    $q->where('name', 'like', "%{$v}%")->orWhere('code', 'like', "%{$v}%")
                )
            )
            ->when($request->category_id,          fn ($q, $v) => $q->where('category_id', $v))
            ->when($request->status === 'disponible', fn ($q) => $q->where('stock', '>', 0))
            ->when($request->status === 'agotado',    fn ($q) => $q->where('stock', '<=', 0))
            ->latest()->paginate(10)->withQueryString();

        return view('admin.products.index', [
            'products'   => $products,
            'categories' => Category::all(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        Product::create($request->validate($this->rules()));

        return redirect()->route('admin.products.index')->with('success', 'Producto creado correctamente.');
    }

    public function update(Request $request, Product $product): RedirectResponse
    {
        $product->update($request->validate($this->rules($product->id)));

        return redirect()->route('admin.products.index')->with('success', 'Producto actualizado correctamente.');
    }

    public function destroy(Product $product): RedirectResponse
    {
        $product->delete();

        return redirect()->route('admin.products.index')->with('success', 'Producto eliminado correctamente.');
    }
}