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
        $orders = ProductionOrder::with(['product', 'user', 'subOrders.assignedUsers'])->latest()->paginate(9);
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
                    $subOrder = $order->subOrders()->create([
                        'proceso'       => $subOrderData['proceso'],
                        'quantity'      => $subOrderData['quantity'] ?? $order->quantity,
                        'status'        => $subOrderData['status'] ?? 'pending',
                        'es_ensamblaje' => filter_var($subOrderData['es_ensamblaje'] ?? false, FILTER_VALIDATE_BOOLEAN),
                        'start_date'    => $subOrderData['start_date'] ?? $order->start_date,
                        'end_date'      => $subOrderData['end_date'] ?? $order->end_date,
                        'notas'         => $subOrderData['notas'] ?? null,
                    ]);

                    if (!empty($subOrderData['operarios'])) {
                        $syncData = [];
                        foreach ($subOrderData['operarios'] as $operarioId) {
                            $syncData[$operarioId] = [
                                'estacion'           => $subOrderData['estacion'] ?? 'General',
                                'pieces_contributed' => 0,
                            ];
                        }
                        $subOrder->assignedUsers()->attach($syncData);
                    }

                    // Solo puede existir UNA fase de ensamblaje por orden.
                    if ($subOrder->es_ensamblaje) {
                        ProductionSubOrder::where('production_order_id', $subOrder->production_order_id)
                            ->where('id', '!=', $subOrder->id)
                            ->update(['es_ensamblaje' => false]);
                    }
                }
            }
        });

        return redirect()->route('admin.orders.index')->with('success', 'Orden de producción y subórdenes creadas correctamente.');
    }

    public function update(Request $request, ProductionOrder $order): RedirectResponse
    {
        $validated = $this->validateOrder($request, $order->id);

        DB::transaction(function () use ($order, $validated, $request) {
            $order->update($validated);
            if ($request->has('sub_orders')) {
                $incomingIds = collect($request->sub_orders)->pluck('id')->filter()->toArray();
                $order->subOrders()->whereNotIn('id', $incomingIds)->delete();

                foreach ($request->sub_orders as $subOrderData) {
                    $subOrder = $order->subOrders()->updateOrCreate(
                        ['id' => $subOrderData['id'] ?? null],
                        [
                            'proceso'       => $subOrderData['proceso'],
                            'quantity'      => $subOrderData['quantity'] ?? $order->quantity,
                            'status'        => $subOrderData['status'] ?? 'pending',
                            'es_ensamblaje' => filter_var($subOrderData['es_ensamblaje'] ?? false, FILTER_VALIDATE_BOOLEAN),
                            'start_date'    => $subOrderData['start_date'] ?? $order->start_date,
                            'end_date'      => $subOrderData['end_date'] ?? $order->end_date,
                            'notas'         => $subOrderData['notas'] ?? null,
                        ]
                    );

                    if (!empty($subOrderData['operarios'])) {
                        $syncData = [];
                        foreach ($subOrderData['operarios'] as $operarioId) {
                            $existingPivot = $subOrder->assignedUsers()->where('user_id', $operarioId)->first();
                            $piecesContributed = $existingPivot ? $existingPivot->pivot->pieces_contributed : 0;

                            $syncData[$operarioId] = [
                                'estacion'           => $subOrderData['estacion'] ?? 'General',
                                'pieces_contributed' => $piecesContributed,
                            ];
                        }
                        $subOrder->assignedUsers()->sync($syncData);
                    } else {
                        $subOrder->assignedUsers()->detach();
                    }

                    if ($subOrder->es_ensamblaje) {
                        ProductionSubOrder::where('production_order_id', $subOrder->production_order_id)
                            ->where('id', '!=', $subOrder->id)
                            ->update(['es_ensamblaje' => false]);
                    }
                }
            }
            // Si no viene "sub_orders" en el request (el caso normal al usar
            // el modal "Editar Orden"), no se toca nada: las subórdenes
            // existentes permanecen intactas.
        });

        return redirect()->route('admin.orders.index')->with('success', 'Orden de producción actualizada correctamente.');
    }

    public function destroy(ProductionOrder $order): RedirectResponse
    {
        $order->delete();

        return redirect()->route('admin.orders.index')->with('success', 'Orden de producción eliminada correctamente.');
    }

    private function validateOrder(Request $request, ?int $ignoreId = null): array
    {
        $uniqueRule = 'unique:production_orders,order_number' . ($ignoreId ? ',' . $ignoreId : '');

        return $request->validate([
            'product_id'                   => 'required|exists:products,id',
            'user_id'                      => 'required|exists:users,id',
            'order_number'                 => 'required|string|max:50|' . $uniqueRule,
            'quantity'                     => 'required|integer|min:1',
            'status'                       => 'required|in:pending,in_progress,completed,cancelled',
            'priority'                     => 'required|in:low,medium,high',
            'estacion'                     => 'nullable|string|max:50',
            'start_date'                   => 'nullable|date',
            'end_date'                     => 'nullable|date|after_or_equal:start_date',

            'sub_orders'                   => 'nullable|array',
            'sub_orders.*.id'              => 'nullable|exists:production_sub_orders,id',
            'sub_orders.*.proceso'         => 'required_with:sub_orders|string|max:100',
            'sub_orders.*.es_ensamblaje'   => 'nullable|boolean',
            'sub_orders.*.operarios'       => 'nullable|array',
            'sub_orders.*.operarios.*'     => 'exists:users,id',
            'sub_orders.*.quantity'        => 'required_with:sub_orders|integer|min:1',
            'sub_orders.*.estacion'        => 'nullable|string|max:50',
        ], [
            'product_id.required'             => 'Selecciona un producto.',
            'user_id.required'                => 'Selecciona un encargado principal.',
            'order_number.required'           => 'El número de orden es obligatorio.',
            'order_number.unique'             => 'Ese número de orden ya existe.',
            'sub_orders.*.proceso.required'    => 'Escribe el nombre del proceso para la suborden.',
            'sub_orders.*.operarios.*.exists'  => 'Uno de los operarios seleccionados no es válido.',
        ]);
    }
}