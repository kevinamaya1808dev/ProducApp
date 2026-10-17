<?php

namespace App\Http\Controllers;

use App\Models\Material;
use App\Models\MaterialStockLog;
use App\Models\Product;
use App\Models\ProductRecipe;
use App\Models\Proveedor;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;

class AlmacenController extends Controller
{
    // La tabla incrusta el historial de cada material en el HTML: solo se carga
    // el de los últimos N días (el historial completo está en /admin/almacen/historial).
    private const DIAS_HISTORIAL_EN_TABLA = 90;

    // Reglas del proveedor, compartidas por registrar/editar material y agregar stock.
    //  - provider_source = 'registrado' → proveedor_id (del directorio)
    //  - provider_source = 'manual'     → proveedor_manual (texto temporal, fuera del directorio)
    // En materiales el proveedor es opcional; al agregar stock es obligatorio.
    private function reglasProveedor(bool $obligatorio): array
    {
        return [
            'provider_source'  => [$obligatorio ? 'required' : 'nullable', 'in:registrado,manual'],
            'proveedor_id'     => array_filter(['nullable', 'exists:proveedores,id', $obligatorio ? 'required_if:provider_source,registrado' : null]),
            'proveedor_manual' => array_filter(['nullable', 'string', 'max:255', $obligatorio ? 'required_if:provider_source,manual' : null]),
        ];
    }

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
        ] + $this->reglasProveedor(false);
    }

    // Columnas de materials que corresponden al proveedor elegido en el formulario.
    // Sin provider_source en la petición no se toca el proveedor que ya tenía el material.
    private function proveedorDelMaterial(array $data): array
    {
        return match ($data['provider_source'] ?? null) {
            'registrado' => ['proveedor_id' => $data['proveedor_id'] ?? null, 'proveedor' => null],
            'manual'     => ['proveedor_id' => null, 'proveedor' => filled($data['proveedor_manual'] ?? null) ? trim($data['proveedor_manual']) : null],
            default      => [],
        };
    }

    private function viewData(): array
    {
        $materials = Material::with([
            'proveedorRegistrado:id,nombre,tipo',
            'stockLogs' => fn ($q) => $q->where('created_at', '>=', now()->subDays(self::DIAS_HISTORIAL_EN_TABLA)),
            'stockLogs.proveedor',
        ])->latest()->get();

        return [
            'materials'         => $materials,
            'products'          => Product::with('recipes.material')->get(),
            'proveedores'       => Proveedor::orderBy('nombre')->get(['id', 'nombre', 'tipo', 'contacto_nombre', 'telefono', 'email']),
            'lowStockMaterials' => $materials->filter(fn ($m) => $m->stock_actual <= $m->stock_minimo),
        ];
    }

    public function index()
    {
        return view('admin.almacen.index', $this->viewData());
    }

    public function storeMaterial(Request $request)
    {
        $data = $request->validate($this->materialRules() + ['stock_actual' => 'required|numeric|min:0']);

        Material::create(
            Arr::only($data, ['name', 'sku', 'unit', 'stock_minimo', 'stock_actual']) + $this->proveedorDelMaterial($data)
        );

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
        $data = $request->validate($this->materialRules($material->id));

        $material->update(
            Arr::only($data, ['name', 'sku', 'unit', 'stock_minimo']) + $this->proveedorDelMaterial($data)
        );

        return redirect()->route('admin.almacen.index')->with('success', '¡Material actualizado correctamente!');
    }

    public function addStock(Request $request, Material $material)
    {
        $data = $request->validate(['quantity_added' => 'required|numeric|min:0.01'] + $this->reglasProveedor(true));

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
