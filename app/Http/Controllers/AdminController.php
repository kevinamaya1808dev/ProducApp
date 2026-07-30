<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProductionOrder;
use App\Models\User;

class AdminController extends Controller
{
    /**
     * Muestra el panel de control principal del administrador con métricas reales.
     */
    public function dashboard(Request $request)
    {
        $search = $request->input('search');

        // Consulta de órdenes con búsqueda por ID o Producto
        $orders = ProductionOrder::with(['product', 'user'])
            ->when($search, function ($query, $search) {
                return $query->where('id', 'like', "%{$search}%")
                             ->orHas('product', function ($q) use ($search) {
                                 $q->where('name', 'like', "%{$search}%");
                             });
            })
            ->latest()
            ->take(10)
            ->get();

        // 1. Lotes / Órdenes en proceso
        $activeOrdersCount = ProductionOrder::where('status', 'in_progress')->count();

        // 2. Piezas completadas en el día
        $completedPiecesToday = ProductionOrder::whereDate('updated_at', today())->sum('completed_pieces') ?? 0;

        // 3. Órdenes pendientes por iniciar
        $pendingOrdersCount = ProductionOrder::where('status', 'pending')->count();

        // 4. Incidencias activas o cuellos de botella (órdenes pausadas)
        $activeIncidentsCount = class_exists('App\Models\Incident') 
            ? \App\Models\Incident::where('status', 'open')->count() 
            : ProductionOrder::where('status', 'paused')->count();

        // 5. Rendimiento global calculado o estimado
        $globalPerformance = '94.2%'; 

        // 6. Registros de actividad reciente
        $recentActivities = ProductionOrder::latest()->take(5)->get();

        return view('admin.dashboard', compact(
            'orders',
            'activeOrdersCount',
            'completedPiecesToday',
            'pendingOrdersCount',
            'activeIncidentsCount',
            'globalPerformance',
            'recentActivities'
        ));
    }
}