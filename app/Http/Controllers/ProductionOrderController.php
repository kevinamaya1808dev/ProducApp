<?php

namespace App\Http\Controllers;

use App\Models\ProductionOrder;
use App\Models\ProductionSubOrder;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class ProductionOrderController extends Controller
{
    public function index(): View
    {
        $orders = ProductionOrder::with(['product', 'user', 'subOrders.user'])->latest()->paginate(9);
        $products = Product::orderBy('name')->get();
        $operarios = User::orderBy('name')->get();

        return view('admin.orders.index', compact('orders', 'products', 'operarios'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateOrder($request);

        DB::transaction(function () use ($validated, $request) {
            $order = ProductionOrder::create($validated);

            if ($request->has('sub_orders')) {
                foreach ($request->sub_orders as $subOrderData) {
                    $order->subOrders()->create([
                        'user_id'    => $subOrderData['user_id'] ?? null,
                        'proceso'    => $subOrderData['proceso'],
                        'estacion'   => $subOrderData['estacion'] ?? null,
                        'quantity'   => $subOrderData['quantity'] ?? $order->quantity,
                        'status'     => $subOrderData['status'] ?? 'pending',
                        'start_date' => $subOrderData['start_date'] ?? $order->start_date,
                        'end_date'   => $subOrderData['end_date'] ?? $order->end_date,
                        'notas'      => $subOrderData['notas'] ?? null,
                    ]);
                }
            }
        });

        return redirect()->route('orders.index')->with('success', 'Orden de producción y subórdenes creadas correctamente.');
    }

    public function update(Request $request, ProductionOrder $order): RedirectResponse
    {
        $validated = $this->validateOrder($request, $order->id);

        DB::transaction(function () use ($order, $validated, $request) {
            $order->update($validated);

            if ($request->has('sub_orders')) {
                // Sincronización simple: eliminamos subórdenes anteriores y recreamos
                $order->subOrders()->delete();

                foreach ($request->sub_orders as $subOrderData) {
                    $order->subOrders()->create([
                        'user_id'    => $subOrderData['user_id'] ?? null,
                        'proceso'    => $subOrderData['proceso'],
                        'estacion'   => $subOrderData['estacion'] ?? null,
                        'quantity'   => $subOrderData['quantity'] ?? $order->quantity,
                        'status'     => $subOrderData['status'] ?? 'pending',
                        'start_date' => $subOrderData['start_date'] ?? $order->start_date,
                        'end_date'   => $subOrderData['end_date'] ?? $order->end_date,
                        'notas'      => $subOrderData['notas'] ?? null,
                    ]);
                }
            }
        });

        return redirect()->route('orders.index')->with('success', 'Orden de producción actualizada correctamente.');
    }

    public function destroy(ProductionOrder $order): RedirectResponse
    {
        $order->delete();

        return redirect()->route('orders.index')->with('success', 'Orden de producción eliminada correctamente.');
    }

    private function validateOrder(Request $request, ?int $ignoreId = null): array
    {
        $uniqueRule = 'unique:production_orders,order_number' . ($ignoreId ? ',' . $ignoreId : '');

        return $request->validate([
            'product_id'                 => 'required|exists:products,id',
            'user_id'                    => 'required|exists:users,id', // Supervisor / Encargado general
            'order_number'               => 'required|string|max:50|' . $uniqueRule,
            'quantity'                   => 'required|integer|min:1',
            'status'                     => 'required|in:pending,in_progress,completed,cancelled',
            'priority'                   => 'required|in:low,medium,high',
            'estacion'                   => 'nullable|string|max:50',
            'start_date'                 => 'nullable|date',
            'end_date'                   => 'nullable|date|after_or_equal:start_date',
            // Validaciones para las subórdenes
            'sub_orders'                 => 'nullable|array',
            'sub_orders.*.proceso'       => 'required_with:sub_orders|string|max:100',
            'sub_orders.*.user_id'       => 'nullable|exists:users,id',
            'sub_orders.*.quantity'      => 'required_with:sub_orders|integer|min:1',
            'sub_orders.*.estacion'      => 'nullable|string|max:50',
        ], [
            'product_id.required'           => 'Selecciona un producto.',
            'user_id.required'              => 'Selecciona un encargado principal.',
            'order_number.required'         => 'El número de orden es obligatorio.',
            'order_number.unique'           => 'Ese número de orden ya existe.',
            'sub_orders.*.proceso.required'  => 'Escribe el nombre del proceso para la suborden.',
            'sub_orders.*.user_id.exists'   => 'El operario asignado a la suborden no existe.',
        ]);
    }
}