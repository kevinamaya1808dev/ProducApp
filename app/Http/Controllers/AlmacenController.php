<?php

namespace App\Http\Controllers;

use App\Models\Material;
use App\Models\Product;
use App\Models\ProductRecipe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AlmacenController extends Controller
{
    public function index()
    {
        $materials = Material::latest()->get();
        $products = Product::with('recipes.material')->get();

        return view('admin.almacen.index', compact('materials', 'products'));
    }

    public function storeMaterial(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'required|string|max:50|unique:materials,sku',
            'unit' => 'required|string|max:50',
            'stock_actual' => 'required|numeric|min:0',
            'stock_minimo' => 'required|numeric|min:0',
            'proveedor' => 'nullable|string|max:255',
        ]);

        Material::create($request->all());

        return redirect()->route('admin.almacen.index')->with('success', 'Material registrado correctamente en el almacén.');
    }

    public function storeRecipe(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'material_id' => 'required|exists:materials,id',
            'quantity_required' => 'required|numeric|min:0.01',
        ]);

        ProductRecipe::updateOrCreate(
            [
                'product_id' => $request->product_id,
                'material_id' => $request->material_id,
            ],
            [
                'quantity_required' => $request->quantity_required,
            ]
        );

        return redirect()->route('admin.almacen.index')->with('success', 'Receta de componente actualizada con éxito.');
    }

    /**
     * Actualiza la información de un material existente.
     */
    public function updateMaterial(Request $request, Material $material)
    {
        $request->validate([
            'name'         => 'required|string|max:255',
            'sku'          => 'required|string|max:50|unique:materials,sku,' . $material->id,
            'unit'         => 'required|string|max:20',
            'stock_actual' => 'required|numeric|min:0',
            'stock_minimo' => 'required|numeric|min:0',
            'proveedor'    => 'nullable|string|max:255',
        ]);

        $material->update([
            'name'         => $request->name,
            'sku'          => $request->sku,
            'unit'         => $request->unit,
            'stock_actual' => $request->stock_actual,
            'stock_minimo' => $request->stock_minimo,
            'proveedor'    => $request->proveedor,
        ]);

        return redirect()->route('admin.almacen.index')
            ->with('success', '¡Material actualizado correctamente!');
    }

    /**
     * Elimina un material del almacén.
     */
    public function destroyMaterial(Material $material)
    {
        $material->delete();

        return redirect()->route('admin.almacen.index')
            ->with('success', '¡Material eliminado del almacén!');
    }
}