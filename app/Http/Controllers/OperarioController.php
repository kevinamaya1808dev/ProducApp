<?php

namespace App\Http\Controllers;

use App\Models\RegistroProduccion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OperarioController extends Controller
{
    /**
     * Muestra el panel principal del operario con datos dinámicos
     */
    public function inicio()
    {
        $userId = Auth::id();

        // Total de piezas hechas hoy por el operario autenticado
        $piezasHoy = RegistroProduccion::where('user_id', $userId)
            ->whereDate('created_at', today())
            ->sum('cantidad');

        // Historial reciente de registros del operario
        $actividadesRecientes = RegistroProduccion::where('user_id', $userId)
            ->latest()
            ->take(5)
            ->get();

        return view('operario.inicio', compact('piezasHoy', 'actividadesRecientes'));
    }

    /**
     * Vista de mis tareas
     */
    public function tareas()
    {
        return view('operario.tareas');
    }

    /**
     * Vista del registro manual de producción
     */
    public function registro()
    {
        return view('operario.registro');
    }

    /**
     * Vista de gestión y reporte de incidencias
     */
    public function incidencias()
    {
        return view('operario.incidencias');
    }

    /**
     * Vista del perfil del operario
     */
    public function perfil()
    {
        return view('operario.perfil');
    }

    /**
     * Procesa y guarda el registro rápido de piezas (+1 o +5)
     */
    public function guardarRegistro(Request $request)
    {
        $request->validate([
            'lote_id' => 'required|string',
            'cantidad' => 'required|integer|min:1',
        ]);

        RegistroProduccion::create([
            'user_id' => Auth::id(),
            'lote_id' => $request->lote_id,
            'cantidad' => $request->cantidad,
            'fecha_registro' => now(),
        ]);

        return redirect()->route('operario.inicio')->with('success', '¡Producción registrada correctamente!');
    }

    /**
     * Registra una nueva incidencia
     */
    public function crearIncidencia(Request $request)
    {
        return redirect()->back()->with('success', 'Incidencia reportada correctamente.');
    }
}