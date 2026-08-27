<?php

namespace App\Http\Controllers;

use App\Models\Material;
use App\Models\Product;
use App\Models\Proveedor;
use Illuminate\Http\Request;

class ProveedorController extends Controller
{
    public function index()
    {
        // Sin paginación: el directorio se filtra en el cliente (ver proveedores-table).
        $materials = Material::with('stockLogs.proveedor')->latest()->get();
        $products = Product::with('recipes.material')->get();
        $proveedores = Proveedor::orderBy('nombre')->get();

        $lowStockMaterials = $materials->filter(fn ($mat) => $mat->stock_actual <= $mat->stock_minimo);

        return view('admin.proveedores.index', compact('materials', 'products', 'proveedores', 'lowStockMaterials'));
    }   
    

    public function store(Request $request)
    {
        $request->validate([
            'nombre'          => 'required|string|max:255',
            'contacto_nombre' => 'nullable|string|max:255',
            'telefono'        => 'nullable|string|max:50',
            'email'           => 'nullable|email|max:255',
            'direccion'       => 'nullable|string|max:500',
        ]);

        Proveedor::create($request->all());

        return redirect()->route('admin.proveedores.index')
            ->with('success', '¡Proveedor registrado correctamente!');
    }

    public function update(Request $request, Proveedor $proveedor)
    {
        $request->validate([
            'nombre'          => 'required|string|max:255',
            'contacto_nombre' => 'nullable|string|max:255',
            'telefono'        => 'nullable|string|max:50',
            'email'           => 'nullable|email|max:255',
            'direccion'       => 'nullable|string|max:500',
        ]);

        $proveedor->update($request->all());

        return redirect()->route('admin.proveedores.index')
            ->with('success', '¡Proveedor actualizado correctamente!');
    }

    public function destroy(Proveedor $proveedor)
    {
        $proveedor->delete();

        return redirect()->route('admin.proveedores.index')
            ->with('success', '¡Proveedor eliminado correctamente!');
    }
}