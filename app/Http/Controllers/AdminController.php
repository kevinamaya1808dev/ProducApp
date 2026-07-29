<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProductionOrder;
use App\Models\User;

class AdminController extends Controller
{
    /**
     * Muestra el panel de control principal del administrador con métricas y órdenes.
     */
    public function dashboard(Request $request)
    {
        $search = $request->input('search');

        // Consulta de órdenes de producción con filtro de búsqueda opcional
        $orders = ProductionOrder::when($search, function ($query, $search) {
                return $query->where('id', 'like', "%{$search}%")
                             ->orHas('product', function ($q) use ($search) {
                                 $q->where('name', 'like', "%{$search}%");
                             });
            })
            ->latest()
            ->take(10)
            ->get();

        // Métricas para las tarjetas KPI del Dashboard
        $activeOrdersCount = ProductionOrder::where('status', 'in_progress')->count();
        $completedPiecesToday = ProductionOrder::whereDate('updated_at', today())->sum('completed_pieces') ?? 0;
        
        // Cálculo o definición del rendimiento global de la planta
        $globalPerformance = '94.2%'; 

        // Conteo de incidencias activas (ajustar si manejas un modelo específico de incidencias)
        $activeIncidentsCount = 0; 

        // Actividad reciente simulada o basada en últimas actualizaciones de órdenes
        $recentActivities = ProductionOrder::latest()->take(5)->get();

        return view('admin.dashboard', compact(
            'orders',
            'activeOrdersCount',
            'completedPiecesToday',
            'globalPerformance',
            'activeIncidentsCount',
            'recentActivities'
        ));
    }
}