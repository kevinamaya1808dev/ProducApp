<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Material;
use App\Models\Product;
use App\Models\ProductRecipe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\MaterialStockLog;

class AlmacenController extends Controller
{
    public function index()
    {
        // NUEVO: se carga el historial de entradas (stockLogs) de una vez junto con
        // el usuario que registró cada una, para evitar hacer una consulta extra
        // por cada material al mostrar su historial en el modal.
        $materials = Material::with('stockLogs.user')->latest()->get();
        $products = Product::with('recipes.material')->get();

        // Conteo de materiales en o por debajo de su stock mínimo, para el banner de alerta.
        $lowStockMaterials = $materials->filter(fn ($mat) => $mat->stock_actual <= $mat->stock_minimo);

        return view('admin.almacen.index', compact('materials', 'products', 'lowStockMaterials'));
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
            'quantity_added' => 'required|numeric|min:0.01',
        ]);

        DB::transaction(function () use ($request, $material) {
            $material->increment('stock_actual', $request->quantity_added);

            // NUEVO: registro en el historial de entradas de stock.
            $material->stockLogs()->create([
                'user_id'           => auth()->id(),
                'quantity_added'    => $request->quantity_added,
                'stock_resultante'  => $material->fresh()->stock_actual,
            ]);
        });

        return redirect()->route('admin.almacen.index')
            ->with('success', "Se agregaron {$request->quantity_added} {$material->unit} de \"{$material->name}\" al stock.");
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
    $logs = MaterialStockLog::with(['material', 'user'])
        ->when($request->filled('material_id'), fn ($query) => $query->where('material_id', $request->material_id))
        ->latest()
        ->paginate(20)
        ->withQueryString();

    $materials = Material::orderBy('name')->get();

    return view('admin.almacen.almacen-historial', compact('logs', 'materials'));
}
}