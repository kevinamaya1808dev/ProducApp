<?php

namespace App\Http\Controllers;

use App\Models\ProductionOrder;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class ProductionOrderController extends Controller
{
    public function index(): View
    {
        $orders = ProductionOrder::with(['product', 'user'])->latest()->paginate(10);
        return view('admin.orders.index', compact('orders'));
    }

    public function create(): View
    {
        $products = Product::all();
        return view('admin.orders.create', compact('products'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'order_number' => 'required|string|max:50|unique:production_orders,order_number',
            'quantity' => 'required|integer|min:1',
            'status' => 'required|in:pending,in_progress,completed,cancelled',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        $validated['user_id'] = Auth::id();

        ProductionOrder::create($validated);

        return redirect()->route('orders.index')->with('success', 'Orden de producción creada correctamente.');
    }

    public function edit(ProductionOrder $order): View
    {
        $products = Product::all();
        return view('admin.orders.edit', compact('order', 'products'));
    }

    public function update(Request $request, ProductionOrder $order): RedirectResponse
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'order_number' => 'required|string|max:50|unique:production_orders,order_number,' . $order->id,
            'quantity' => 'required|integer|min:1',
            'status' => 'required|in:pending,in_progress,completed,cancelled',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        $order->update($validated);

        return redirect()->route('orders.index')->with('success', 'Orden de producción actualizada correctamente.');
    }

    public function destroy(ProductionOrder $order): RedirectResponse
    {
        $order->delete();

        return redirect()->route('orders.index')->with('success', 'Orden de producción eliminada correctamente.');
    }
}