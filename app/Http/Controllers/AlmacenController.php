<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Material;
use App\Models\MaterialStockLog;
use App\Models\Product;
use App\Models\ProductRecipe;
use App\Models\Proveedor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class AlmacenController extends Controller
{
    public function index()
{
    $materials = Material::with('stockLogs.proveedor')->latest()->get();
    $products = Product::with('recipes.material')->get();
    $proveedores = Proveedor::orderBy('nombre')->get(); // para el <select> del switch

    $lowStockMaterials = $materials->filter(fn ($mat) => $mat->stock_actual <= $mat->stock_minimo);

    return view('admin.almacen.index', compact('materials', 'products', 'lowStockMaterials', 'proveedores'));
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
     * CORRECCIÓN: "stock_actual" ya no se acepta aquí, ni siquiera si alguien
     * lo manda manipulando el formulario/HTML. El stock ahora solo se modifica
     * a través de addStock(), para que quede como una entrada registrada y no
     * como un valor sobreescrito arbitrariamente.
     */
    public function updateMaterial(Request $request, Material $material)
    {
        $request->validate([
            'name'         => 'required|string|max:255',
            'sku'          => 'required|string|max:50|unique:materials,sku,' . $material->id,
            'unit'         => 'required|string|max:20',
            'stock_minimo' => 'required|numeric|min:0',
            'proveedor'    => 'nullable|string|max:255',
        ]);

        $material->update([
            'name'         => $request->name,
            'sku'          => $request->sku,
            'unit'         => $request->unit,
            'stock_minimo' => $request->stock_minimo,
            'proveedor'    => $request->proveedor,
        ]);

        return redirect()->route('admin.almacen.index')
            ->with('success', '¡Material actualizado correctamente!');
    }

    /**
     * Agrega unidades al stock existente de un material (suma, no reemplaza)
     * y deja registrado el ingreso en el historial (quién, cuánto y cuándo).
     */
    public function addStock(Request $request, Material $material)
{
    $request->validate([
        'quantity_added'   => 'required|numeric|min:0.01',
        'provider_source'  => 'required|in:registrado,manual',
        'proveedor_id'     => 'required_if:provider_source,registrado|nullable|exists:proveedores,id',
        'proveedor_manual' => 'required_if:provider_source,manual|nullable|string|max:255',
    ]);

    $material->increment('stock_actual', $request->quantity_added);

    MaterialStockLog::create([
        'material_id'      => $material->id,
        'user_id'          => auth()->id(),
        'quantity_added'   => $request->quantity_added,
        'stock_resultante' => $material->stock_actual,
        'proveedor_id'     => $request->provider_source === 'registrado' ? $request->proveedor_id : null,
        'proveedor_manual' => $request->provider_source === 'manual' ? $request->proveedor_manual : null,
    ]);

    Cache::forget('sidebar.low_stock_materials');

    return redirect()->route('admin.almacen.index')->with('success', '¡Stock actualizado correctamente!');
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

    public function historial(Request $request)
{
    $logs = MaterialStockLog::with(['material', 'user', 'proveedor'])
        ->when($request->filled('material_id'), fn ($query) => $query->where('material_id', $request->material_id))
        ->latest()
        ->paginate(20)
        ->withQueryString();

    $materials = Material::orderBy('name')->get();

    return view('admin.almacen.almacen-historial', compact('logs', 'materials'));
}
}