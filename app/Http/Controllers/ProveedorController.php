<?php

namespace App\Http\Controllers;

use App\Models\Material;
use App\Models\Product;
use App\Models\Proveedor;
use Illuminate\Http\Request;

class ProveedorController extends Controller
{
    private function rules(): array
    {
        return [
            'nombre'          => 'required|string|max:255',
            'contacto_nombre' => 'nullable|string|max:255',
            'telefono'        => 'nullable|string|max:50',
            'email'           => 'nullable|email|max:255',
            'direccion'       => 'nullable|string|max:500',
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
        return view('admin.proveedores.index', $this->viewData());
    }

    public function store(Request $request)
    {
        Proveedor::create($request->validate($this->rules()));

        return redirect()->route('admin.proveedores.index')->with('success', '¡Proveedor registrado correctamente!');
    }

    public function update(Request $request, Proveedor $proveedor)
    {
        $proveedor->update($request->validate($this->rules()));

        return redirect()->route('admin.proveedores.index')->with('success', '¡Proveedor actualizado correctamente!');
    }

    public function destroy(Proveedor $proveedor)
    {
        $proveedor->delete();

        return redirect()->route('admin.proveedores.index')->with('success', '¡Proveedor eliminado correctamente!');
    }
}