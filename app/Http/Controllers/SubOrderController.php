<?php

namespace App\Http\Controllers;

use App\Models\ProductionSubOrder; // O el nombre del modelo que utilices para las subórdenes
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\RedirectResponse;

class SubOrderController extends Controller
{
    /**
     * Almacena una nueva suborden.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'production_order_id' => 'required|exists:production_orders,id',
            'proceso'             => 'required|string|max:100',
            'quantity'            => 'required|integer|min:1',
            'status'              => 'required|in:pending,in_progress,completed,cancelled',
            'start_date'          => 'nullable|date',
            'end_date'            => 'nullable|date|after_or_equal:start_date',
            'notas'               => 'nullable|string|max:500',
        ]);

        ProductionSubOrder::create($validated);

        return redirect()->back()->with('success', 'Suborden creada correctamente.');
    }

    /**
     * Actualiza la información o estado de una suborden.
     */
    public function update(Request $request, ProductionSubOrder $subOrder): RedirectResponse
    {
        $validated = $request->validate([
            'proceso'    => 'sometimes|required|string|max:100',
            'status'     => 'sometimes|required|in:pending,in_progress,completed,cancelled',
            'quantity'   => 'sometimes|required|integer|min:1',
            'notas'      => 'nullable|string|max:500',
        ]);

        $subOrder->update($validated);

        return redirect()->back()->with('success', 'Suborden actualizada correctamente.');
    }

    /**
     * Registra el avance de producción del operario, actualiza las piezas aportadas
     * en la tabla pivote y descuenta automáticamente los materiales del almacén.
     */
    public function registerProgress(Request $request, ProductionSubOrder $subOrder): RedirectResponse
    {
        $request->validate([
            'quantity_produced' => 'required|integer|min:1',
        ]);

        $user = auth()->user();
        $piecesProduced = $request->input('quantity_produced');

        DB::transaction(function () use ($subOrder, $user, $piecesProduced) {
            
            // 1. Actualizar piezas aportadas por el operario en la tabla pivote
            $pivotData = $subOrder->assignedUsers()->where('user_id', $user->id)->first();
            
            if ($pivotData) {
                $currentPieces = $pivotData->pivot->pieces_contributed;
                $subOrder->assignedUsers()->updateExistingPivot($user->id, [
                    'pieces_contributed' => $currentPieces + $piecesProduced
                ]);
            } else {
                $subOrder->assignedUsers()->attach($user->id, [
                    'estacion'           => 'General',
                    'pieces_contributed' => $piecesProduced
                ]);
            }

            // 2. Descontar el inventario de materiales basado en la receta del producto
            $product = $subOrder->productionOrder->product ?? null;
            
            if ($product && $product->recipes) {
                foreach ($product->recipes as $recipe) {
                    $material = $recipe->material;
                    if ($material) {
                        $totalQuantityNeeded = $recipe->quantity_required * $piecesProduced;
                        $newStock = max(0, $material->stock_actual - $totalQuantityNeeded);
                        
                        $material->update([
                            'stock_actual' => $newStock
                        ]);
                    }
                }
            }

            // 3. Cambiar el estado a 'in_progress' si estaba pendiente
            if ($subOrder->status === 'pending') {
                $subOrder->update(['status' => 'in_progress']);
            }
        });

        return redirect()->back()->with('success', '¡Avance registrado y stock descontado del almacén exitosamente!');
    }

    /**
     * Elimina una suborden.
     */
    public function destroy(ProductionSubOrder $subOrder): RedirectResponse
    {
        $subOrder->delete();

        return redirect()->back()->with('success', 'Suborden eliminada correctamente.');
    }
}