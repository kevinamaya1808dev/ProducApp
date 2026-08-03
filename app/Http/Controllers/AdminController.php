<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProductionOrder;
use App\Models\RegistroProduccion;
use App\Models\Incidence;
use App\Models\User;
use Carbon\Carbon;

class AdminController extends Controller
{
    /**
     * Muestra el panel de control principal del administrador con métricas reales.
     */
    public function dashboard(Request $request)
    {
        $search = $request->input('search');
        $statusFilter = $request->input('status');
        $dateFilter = $request->input('date');

        // 1. Consulta base de órdenes con Eager Loading (incluyendo la categoría del producto para la vista)
        $query = ProductionOrder::with(['product.category', 'user', 'registros']);

        // 2. Filtros provenientes del Modal
        if ($statusFilter) {
            $query->where('status', $statusFilter);
        }

        if ($dateFilter) {
            // Filtramos por la fecha de creación de la orden
            $query->whereDate('created_at', $dateFilter);
        }

        // 3. Búsqueda general por texto (barra de búsqueda superior)
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhere('id', 'like', "%{$search}%")
                  ->orWhereHas('product', function ($subq) use ($search) {
                      $subq->where('name', 'like', "%{$search}%");
                  })
                  ->orWhereHas('user', function ($subq) use ($search) {
                      $subq->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // Paginación adjuntando los parámetros de la URL (filtros y búsquedas)
        $orders = $query->latest()->paginate(10)->withQueryString();

        // 4. Cálculo de KPIs (Tarjetas superiores)
        
        // Lotes Activos
        $activeOrdersCount = ProductionOrder::where('status', 'in_progress')->count();

        // Piezas terminadas hoy (Verificando que la clase exista para evitar errores de despliegue)
        $completedPiecesToday = class_exists(RegistroProduccion::class) 
            ? RegistroProduccion::whereDate('created_at', today())->sum('cantidad') 
            : 0;

        // Incidencias Activas (Basado en el enum 'pendiente' de tu migración Incidence)
        $activeIncidentsCount = Incidence::where('status', 'pendiente')->count();

        // Rendimiento global dinámico (Promedio del porcentaje de avance de las órdenes en progreso)
        $activeOrdersData = ProductionOrder::with('registros')->where('status', 'in_progress')->get();
        if ($activeOrdersData->count() > 0) {
            $promedio = $activeOrdersData->avg(function ($order) {
                return $order->porcentaje_avance; // Usa el Accessor de tu modelo
            });
            $globalPerformance = round($promedio, 1) . '%';
        } else {
            $globalPerformance = '0%';
        }

        // 5. Mapeo de Actividad Reciente para el feed lateral
        $recentOrders = ProductionOrder::latest()->take(5)->get();
        $recentActivities = $recentOrders->map(function ($order) {
            $action = match ($order->status) {
                'pending' => 'Nueva orden registrada',
                'in_progress' => 'Producción iniciada',
                'completed' => 'Lote completado',
                'cancelled' => 'Orden cancelada',
                default => 'Actualización en lote'
            };

            // Creamos un objeto genérico para que la vista lo lea sin fallos
            return (object)[
                'description' => "{$action}: " . ($order->order_number ?? 'ORD-'.$order->id),
                'created_at' => $order->created_at
            ];
        });

        return view('admin.dashboard', compact(
            'orders',
            'activeOrdersCount',
            'completedPiecesToday',
            'globalPerformance',
            'activeIncidentsCount',
            'recentActivities'
        ));
    }

    /**
     * Método para gestionar la exportación desde el modal
     */
    public function export(Request $request)
    {
        $request->validate([
            'format' => 'required|in:excel,pdf,csv',
            'sections' => 'required|array'
        ]);

        // Aquí integrarías Laravel Excel (Maatwebsite) o DomPDF
        // Ejemplo de retorno temporal:
        return back()->with('success', 'La exportación en formato ' . strtoupper($request->format) . ' ha comenzado.');
    }
}