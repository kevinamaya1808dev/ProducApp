<?php

namespace App\Http\Controllers;

use App\Models\ProductionOrder;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class ProductionOrderController extends Controller
{
    public function index(): View
    {
        $orders = ProductionOrder::with(['product', 'user'])->latest()->paginate(9);
        $products = Product::orderBy('name')->get();
        $operarios = User::orderBy('name')->get();

        return view('admin.orders.index', compact('orders', 'products', 'operarios'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateOrder($request);

        ProductionOrder::create($validated);

        return redirect()->route('orders.index')->with('success', 'Orden de producción creada correctamente.');
    }

    public function update(Request $request, ProductionOrder $order): RedirectResponse
    {
        $validated = $this->validateOrder($request, $order->id);

        $order->update($validated);

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
            'product_id'   => 'required|exists:products,id',
            'user_id'      => 'required|exists:users,id',
            'order_number' => 'required|string|max:50|' . $uniqueRule,
            'quantity'     => 'required|integer|min:1',
            'status'       => 'required|in:pending,in_progress,completed,cancelled',
            'priority'     => 'required|in:low,medium,high',
            'estacion'     => 'nullable|string|max:50',
            'start_date'   => 'nullable|date',
            'end_date'     => 'nullable|date|after_or_equal:start_date',
        ], [
            'product_id.required'   => 'Selecciona un producto.',
            'user_id.required'      => 'Selecciona un operario asignado.',
            'order_number.required' => 'El número de orden es obligatorio.',
            'order_number.unique'   => 'Ese número de orden ya existe.',
            'quantity.required'     => 'La cantidad es obligatoria.',
            'quantity.min'          => 'La cantidad debe ser mayor a 0.',
        ]);
    }
}