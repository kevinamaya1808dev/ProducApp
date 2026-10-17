<?php

namespace App\Http\Controllers;

use App\Models\Proveedor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class ProveedorController extends Controller
{
    private function rules(): array
    {
        return [
            'nombre'          => 'required|string|max:255',
            'tipo'            => ['sometimes', Rule::in(Proveedor::TIPOS)],   // si no viene: 'empresa' al crear / se conserva al editar
            'contacto_nombre' => 'nullable|string|max:255',
            'telefono'        => 'nullable|string|max:50',
            'email'           => 'nullable|email|max:255',
            'direccion'       => 'nullable|string|max:500',
        ];
    }

    private function viewData(): array
    {
        // La vista de proveedores solo usa $proveedores.
        return [
            'proveedores' => Proveedor::orderBy('nombre')->get(),
        ];
    }

    public function index(Request $request)
    {
        // Pestaña con la que abre la página (?tipo=particular). Por defecto, empresas.
        $tipo = in_array($request->query('tipo'), Proveedor::TIPOS, true) ? $request->query('tipo') : Proveedor::EMPRESA;

        return view('admin.proveedores.index', $this->viewData() + ['tipoInicial' => $tipo]);
    }

    public function store(Request $request)
    {
        $proveedor = Proveedor::create($request->validate($this->rules()));

        // Alta rápida desde los modales de Almacén (fetch/JSON): devuelve el proveedor creado.
        if ($request->expectsJson()) {
            return response()->json([
                'proveedor' => $proveedor->only(['id', 'nombre', 'tipo', 'contacto_nombre', 'telefono', 'email']),
            ], 201);
        }

        return redirect()->route('admin.proveedores.index', ['tipo' => $proveedor->tipo])
            ->with('success', '¡Proveedor registrado correctamente!');
    }

    public function update(Request $request, Proveedor $proveedor)
    {
        $proveedor->update($request->validate($this->rules()));

        return redirect()->route('admin.proveedores.index', ['tipo' => $proveedor->tipo])
            ->with('success', '¡Proveedor actualizado correctamente!');
    }

    public function destroy(Proveedor $proveedor)
    {
        // Al borrar el proveedor la BD pone en NULL su enlace en materiales e ingresos de stock,
        // y el historial perdería quién entregó el material. Antes de borrarlo, su nombre se
        // conserva como texto en cada registro que lo usaba.
        DB::transaction(function () use ($proveedor) {
            $proveedor->ingresos()->update(['proveedor_manual' => $proveedor->nombre]);
            $proveedor->materiales()->update(['proveedor' => $proveedor->nombre]);
            $proveedor->delete();
        });

        return redirect()->route('admin.proveedores.index', ['tipo' => $proveedor->tipo])
            ->with('success', '¡Proveedor eliminado correctamente!');
    }
}
