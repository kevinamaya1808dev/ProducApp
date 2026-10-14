<?php

namespace App\Http\Controllers;

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
        // La vista de proveedores solo usa $proveedores. Antes también cargaba todos
        // los materiales con TODO su historial de stock y todos los productos con recetas.
        return [
            'proveedores' => Proveedor::orderBy('nombre')->get(),
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