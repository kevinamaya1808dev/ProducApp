<?php

namespace App\Http\Controllers;

use App\Models\Incidence;
use App\Models\ProductionOrder;
use App\Models\RegistroProduccion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OperarioController extends Controller
{
    public function inicio()
    {
        $userId = Auth::id();

        // Orden de producción activa asignada al operario
        $ordenActiva = ProductionOrder::with('product')
            ->where('user_id', $userId)
            ->where('status', 'in_progress')
            ->latest()
            ->first();

        // Total de piezas hechas hoy por el operario (todas las órdenes)
        $piezasHoy = RegistroProduccion::where('user_id', $userId)
            ->whereDate('created_at', today())
            ->sum('cantidad');

        // Piezas registradas hoy específicamente para la orden activa
        $piezasOrdenActiva = 0;
        if ($ordenActiva) {
            $piezasOrdenActiva = RegistroProduccion::where('production_order_id', $ordenActiva->id)
                ->whereDate('created_at', today())
                ->sum('cantidad');
        }

        $actividadesRecientes = RegistroProduccion::where('user_id', $userId)
            ->latest()
            ->take(5)
            ->get();

        return view('operario.inicio', compact('ordenActiva', 'piezasHoy', 'piezasOrdenActiva', 'actividadesRecientes'));
    }

    public function tareas()
    {
        $ordenes = ProductionOrder::with('product')
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('operario.tareas', compact('ordenes'));
    }

    public function registro()
    {
        return view('operario.registro');
    }

    public function incidencias()
    {
        return view('operario.incidencias');
    }

    public function perfil()
    {
        return view('operario.perfil');
    }

    public function guardarRegistro(Request $request)
    {
        $request->validate([
            'production_order_id' => 'required|exists:production_orders,id',
            'cantidad' => 'required|integer|min:1',
        ]);

        RegistroProduccion::create([
            'user_id' => Auth::id(),
            'production_order_id' => $request->production_order_id,
            'cantidad' => $request->cantidad,
            'fecha_registro' => now(),
        ]);

        return redirect()->route('operario.inicio')->with('success', '¡Producción registrada correctamente!');
    }

    public function crearIncidencia(Request $request)
    {
        $request->validate([
            'production_order_id' => 'required|exists:production_orders,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        Incidence::create([
            'production_order_id' => $request->production_order_id,
            'user_id' => Auth::id(),
            'title' => $request->title,
            'description' => $request->description,
        ]);

        return redirect()->back()->with('success', 'Incidencia reportada correctamente.');
    }


// ... dentro de la clase OperarioController, junto a los demás métodos:

public function actualizarEstacion(Request $request, ProductionOrder $productionOrder)
{
    // Seguridad: solo el operario asignado puede modificar su propia orden
    if ($productionOrder->user_id !== Auth::id()) {
        abort(403);
    }

    $request->validate([
        'estacion' => 'required|string|max:50',
    ]);

    $productionOrder->update(['estacion' => $request->estacion]);

    return redirect()->route('operario.inicio')->with('success', 'Estación asignada correctamente.');
}

}