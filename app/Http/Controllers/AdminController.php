<?php

namespace App\Http\Controllers;

use App\Models\Incidence;
use App\Models\ProductionOrder;
use App\Models\RegistroProduccion;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard(Request $request)
    {
        $orders = ProductionOrder::with(['product.category', 'user', 'registros', 'subOrders.assignedUsers'])
            ->when($request->status, fn ($q, $v) => $q->where('status', $v))
            ->when($request->date,   fn ($q, $v) => $q->whereDate('created_at', $v))
            ->when($request->search, fn ($q, $v) => $q->where(fn ($q) =>
                $q->where('order_number', 'like', "%{$v}%")
                  ->orWhere('id', 'like', "%{$v}%")
                  ->orWhereHas('product', fn ($s) => $s->where('name', 'like', "%{$v}%"))
                  ->orWhereHas('user',    fn ($s) => $s->where('name', 'like', "%{$v}%"))
            ))
            ->latest()->paginate(10)->withQueryString();

        // porcentaje_avance lee las subórdenes (antes cargaba 'registros', que no usa: N+1 por orden).
        $activeOrders = ProductionOrder::with('subOrders')->where('status', 'in_progress')->get();

        $recentActivities = ProductionOrder::latest()->take(5)->get()->map(fn ($o) => (object)[
            'description' => match ($o->status) {
                'pending'     => 'Nueva orden registrada',
                'in_progress' => 'Producción iniciada',
                'completed'   => 'Lote completado',
                'cancelled'   => 'Orden cancelada',
                default       => 'Actualización en lote',
            } . ': ' . ($o->order_number ?? 'ORD-' . $o->id),
            'created_at' => $o->created_at,
        ]);

        return view('admin.dashboard', [
            'orders'               => $orders,
            'activeOrdersCount'    => ProductionOrder::where('status', 'in_progress')->count(),
            'completedPiecesToday' => RegistroProduccion::whereDate('created_at', today())->sum('cantidad'),
            'activeIncidentsCount' => Incidence::where('status', 'pendiente')->count(),
            'globalPerformance'    => $activeOrders->isNotEmpty()
                ? round($activeOrders->avg(fn ($o) => $o->porcentaje_avance), 1) . '%'
                : '0%',
            'recentActivities'     => $recentActivities,
        ]);
    }
}
