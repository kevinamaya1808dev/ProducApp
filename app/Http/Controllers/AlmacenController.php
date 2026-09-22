<?php

namespace App\Http\Controllers;

use App\Models\Material;
use App\Models\MaterialStockLog;
use App\Models\Product;
use App\Models\ProductRecipe;
use App\Models\Proveedor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class AlmacenController extends Controller
{
    // Reglas base compartidas entre storeMaterial() y updateMaterial().
    // stock_actual se incluye solo en store; updateMaterial lo excluye
    // intencionalmente (solo se modifica vía addStock).
    private function materialRules(?int $ignoreId = null): array
    {
        return [
            'name'         => 'required|string|max:255',
            'sku'          => 'required|string|max:50|unique:materials,sku' . ($ignoreId ? ",$ignoreId" : ''),
            'unit'         => 'required|string|max:20',
            'stock_minimo' => 'required|numeric|min:0',
            'proveedor'    => 'nullable|string|max:255',
        ];
    }

    private function viewData(): array
    {
        $materials = Material::with('stockLogs.proveedor')->latest()->get();

        return [
            'materials'         => $materials,
            'products'          => Product::with('recipes.material')->get(),
            'proveedores'       => Proveedor::orderBy('nombre')->get(),
            'lowStockMaterials' => $materials->filter(fn ($m) => $m->stock_actual <= $m->stock_minimo),
        ];
    }

    public function index()
    {
        return view('admin.almacen.index', $this->viewData());
    }

    public function storeMaterial(Request $request)
    {
        Material::create($request->validate(
            $this->materialRules() + ['stock_actual' => 'required|numeric|min:0']
        ));

        return redirect()->route('admin.almacen.index')->with('success', 'Material registrado correctamente en el almacén.');
    }

    public function storeRecipe(Request $request)
    {
        $data = $request->validate([
            'product_id'        => 'required|exists:products,id',
            'material_id'       => 'required|exists:materials,id',
            'quantity_required' => 'required|numeric|min:0.01',
        ]);

        ProductRecipe::updateOrCreate(
            ['product_id' => $data['product_id'], 'material_id' => $data['material_id']],
            ['quantity_required' => $data['quantity_required']]
        );

        return redirect()->route('admin.almacen.index')->with('success', 'Receta de componente actualizada con éxito.');
    }

    // stock_actual no se acepta aquí: solo se modifica a través de addStock()
    // para que quede como entrada registrada y no como valor sobreescrito.
    public function updateMaterial(Request $request, Material $material)
    {
        $material->update($request->validate($this->materialRules($material->id)));

        return redirect()->route('admin.almacen.index')->with('success', '¡Material actualizado correctamente!');
    }

    public function addStock(Request $request, Material $material)
    {
        $data = $request->validate([
            'quantity_added'   => 'required|numeric|min:0.01',
            'provider_source'  => 'required|in:registrado,manual',
            'proveedor_id'     => 'required_if:provider_source,registrado|nullable|exists:proveedores,id',
            'proveedor_manual' => 'required_if:provider_source,manual|nullable|string|max:255',
        ]);

        $material->increment('stock_actual', $data['quantity_added']);

        MaterialStockLog::create([
            'material_id'      => $material->id,
            'user_id'          => auth()->id(),
            'quantity_added'   => $data['quantity_added'],
            'stock_resultante' => $material->stock_actual,
            'proveedor_id'     => $data['provider_source'] === 'registrado' ? $data['proveedor_id']     : null,
            'proveedor_manual' => $data['provider_source'] === 'manual'      ? $data['proveedor_manual'] : null,
        ]);

        Cache::forget('sidebar.low_stock_materials');

        return redirect()->route('admin.almacen.index')->with('success', '¡Stock actualizado correctamente!');
    }

    public function destroyMaterial(Material $material)
    {
        $material->delete();

        return redirect()->route('admin.almacen.index')->with('success', '¡Material eliminado del almacén!');
    }

    public function historial(Request $request)
    {
        $logs = MaterialStockLog::with(['material', 'user', 'proveedor'])
            ->when($request->filled('material_id'), fn ($q) => $q->where('material_id', $request->material_id))
            ->latest()->paginate(20)->withQueryString();

        return view('admin.almacen.almacen-historial', [
            'logs'      => $logs,
            'materials' => Material::orderBy('name')->get(),
        ]);
    }
}