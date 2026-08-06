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
        // Añadimos assignedUsers para cargar correctamente la relación pivote de las subórdenes
        $orders = ProductionOrder::with(['product', 'user', 'subOrders.assignedUsers'])->latest()->paginate(9);
        $products = Product::orderBy('name')->get();
        // Solo traemos a los usuarios que puedan ser operarios (ajusta si tienes un whereRole)
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
                        'proceso'    => $subOrderData['proceso'],
                        'quantity'   => $subOrderData['quantity'] ?? $order->quantity,
                        'status'     => $subOrderData['status'] ?? 'pending',
                        'start_date' => $subOrderData['start_date'] ?? $order->start_date,
                        'end_date'   => $subOrderData['end_date'] ?? $order->end_date,
                        'notas'      => $subOrderData['notas'] ?? null,
                    ]);

                    // Asignar múltiples operarios a través de la tabla pivote
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
                // Obtener IDs de las subórdenes que vienen en la petición (para no borrarlas)
                $incomingIds = collect($request->sub_orders)->pluck('id')->filter()->toArray();

                // Borrar solo las subórdenes que ya no existen en la petición
                $order->subOrders()->whereNotIn('id', $incomingIds)->delete();

                foreach ($request->sub_orders as $subOrderData) {
                    // Actualizar si existe el ID, o crear una nueva si no
                    $subOrder = $order->subOrders()->updateOrCreate(
                        ['id' => $subOrderData['id'] ?? null],
                        [
                            'proceso'    => $subOrderData['proceso'],
                            'quantity'   => $subOrderData['quantity'] ?? $order->quantity,
                            'status'     => $subOrderData['status'] ?? 'pending',
                            'start_date' => $subOrderData['start_date'] ?? $order->start_date,
                            'end_date'   => $subOrderData['end_date'] ?? $order->end_date,
                            'notas'      => $subOrderData['notas'] ?? null,
                        ]
                    );

                    // Sincronizar operarios en la tabla pivote sin perder su progreso actual
                    if (!empty($subOrderData['operarios'])) {
                        $syncData = [];
                        foreach ($subOrderData['operarios'] as $operarioId) {
                            // Buscar si el operario ya estaba asignado para conservar sus piezas aportadas
                            $existingPivot = $subOrder->assignedUsers()->where('user_id', $operarioId)->first();
                            $piecesContributed = $existingPivot ? $existingPivot->pivot->pieces_contributed : 0;

                            $syncData[$operarioId] = [
                                'estacion'           => $subOrderData['estacion'] ?? 'General',
                                'pieces_contributed' => $piecesContributed,
                            ];
                        }
                        $subOrder->assignedUsers()->sync($syncData);
                    } else {
                        // Si se quitaron todos los operarios de esta suborden
                        $subOrder->assignedUsers()->detach();
                    }
                }
            } else {
                // Si el request no trae ninguna suborden, borramos las existentes
                $order->subOrders()->delete();
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
            'product_id'                   => 'required|exists:products,id',
            'user_id'                      => 'required|exists:users,id', // Supervisor / Encargado general
            'order_number'                 => 'required|string|max:50|' . $uniqueRule,
            'quantity'                     => 'required|integer|min:1',
            'status'                       => 'required|in:pending,in_progress,completed,cancelled',
            'priority'                     => 'required|in:low,medium,high',
            'estacion'                     => 'nullable|string|max:50',
            'start_date'                   => 'nullable|date',
            'end_date'                   => 'nullable|date|after_or_equal:start_date',
            
            // Validaciones para las subórdenes
            'sub_orders'                   => 'nullable|array',
            'sub_orders.*.id'              => 'nullable|exists:production_sub_orders,id',
            'sub_orders.*.proceso'         => 'required_with:sub_orders|string|max:100',
            
            // Validar arreglo de operarios en lugar de un solo user_id
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