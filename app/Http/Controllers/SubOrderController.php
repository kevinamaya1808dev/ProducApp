<?php

namespace App\Http\Controllers;

use App\Models\ProductionSubOrder;
use App\Models\Incidence;
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
            // CORRECCIÓN: antes faltaban estas dos reglas, así que el formulario
            // los enviaba pero el controlador los descartaba silenciosamente.
            'es_ensamblaje'       => 'nullable|boolean',
            'operarios'           => 'nullable|array',
            'operarios.*'         => 'exists:users,id',
            'start_date'          => 'nullable|date',
            'end_date'            => 'nullable|date|after_or_equal:start_date',
            'notas'               => 'nullable|string|max:500',
        ]);

        // CORRECCIÓN: toda suborden nueva inicia como 'pending' siempre.
        // Ya no depende de un campo oculto en el formulario (más seguro:
        // no se puede alterar manipulando el HTML desde el navegador).
        $validated['status'] = 'pending';
        $validated['es_ensamblaje'] = $request->boolean('es_ensamblaje');

        $operariosSeleccionados = $validated['operarios'] ?? [];
        unset($validated['operarios']);

        DB::transaction(function () use ($validated, $operariosSeleccionados) {
            $subOrder = ProductionSubOrder::create($validated);

            // CORRECCIÓN: antes los operarios elegidos en el modal nunca se
            // guardaban en la tabla pivote. Se asignan aquí con 0 piezas
            // aportadas de inicio.
            if (!empty($operariosSeleccionados)) {
                $syncData = collect($operariosSeleccionados)->mapWithKeys(fn ($userId) => [
                    $userId => ['estacion' => 'General', 'pieces_contributed' => 0],
                ])->toArray();

                $subOrder->assignedUsers()->sync($syncData);
            }
        });

        return redirect()->back()->with('success', 'Suborden creada correctamente.');
    }

    /**
     * Actualiza la información o estado de una suborden.
     */
    public function update(Request $request, ProductionSubOrder $subOrder): RedirectResponse
    {
        $validated = $request->validate([
            'proceso'          => 'sometimes|required|string|max:100',
            'status'           => 'sometimes|required|in:pending,in_progress,completed,cancelled',
            'quantity'         => 'sometimes|required|integer|min:1',
            // CORRECCIÓN: el nombre real de la columna es completed_pieces
            // (el formulario mandaba completed_quantity, que no existía).
            'completed_pieces' => 'sometimes|required|integer|min:0',
            'es_ensamblaje'    => 'nullable|boolean',
            'operarios'        => 'nullable|array',
            'operarios.*'      => 'exists:users,id',
            'notas'            => 'nullable|string|max:500',
        ]);

        $validated['es_ensamblaje'] = $request->boolean('es_ensamblaje');

        $operariosSeleccionados = $request->has('operarios') ? ($validated['operarios'] ?? []) : null;
        unset($validated['operarios']);

        DB::transaction(function () use ($subOrder, $validated, $operariosSeleccionados) {
            $subOrder->update($validated);

            // CORRECCIÓN: se sincronizan los operarios elegidos en el modal.
            // Se preservan estación y piezas aportadas de quienes ya estaban
            // asignados, para no perder su avance registrado.
            if ($operariosSeleccionados !== null) {
                $pivotActual = $subOrder->assignedUsers()->get()->keyBy('id');

                $syncData = collect($operariosSeleccionados)->mapWithKeys(function ($userId) use ($pivotActual) {
                    $anterior = $pivotActual->get($userId);
                    return [$userId => [
                        'estacion'           => $anterior->pivot->estacion ?? 'General',
                        'pieces_contributed' => $anterior->pivot->pieces_contributed ?? 0,
                    ]];
                })->toArray();

                $subOrder->assignedUsers()->sync($syncData);
            }
        });

        return redirect()->back()->with('success', 'Suborden actualizada correctamente.');
    }

    /**
     * Registra el avance de producción del operario, actualiza las piezas aportadas
     * en la tabla pivote y descuenta automáticamente los materiales del almacén.
     * Si algún material queda en o por debajo de su stock mínimo, genera una
     * incidencia automática para que el administrador lo vea.
     */
    public function registerProgress(Request $request, ProductionSubOrder $subOrder): RedirectResponse
    {
        $request->validate([
            'quantity_produced' => 'required|integer|min:1',
        ]);

        $user = auth()->user();
        $piecesProduced = $request->input('quantity_produced');

        DB::transaction(function () use ($subOrder, $user, $piecesProduced) {

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

                        // NUEVO: incidencia automática de stock bajo.
                        // production_order_id es obligatorio en la tabla incidences,
                        // así que se toma de la orden a la que pertenece esta suborden.
                        if ($newStock <= $material->stock_minimo) {
                            $tituloIncidencia = "Stock bajo: {$material->name} ({$material->sku})";

                            // Evita crear una incidencia duplicada si ya existe una
                            // abierta para el mismo material.
                            $yaExisteAbierta = Incidence::where('title', $tituloIncidencia)
                                ->whereIn('status', ['pendiente', 'en_proceso'])
                                ->exists();

                            if (!$yaExisteAbierta) {
                                Incidence::create([
                                    'production_order_id' => $subOrder->production_order_id,
                                    'user_id'              => $user->id,
                                    'title'                 => $tituloIncidencia,
                                    'description'           => "El material \"{$material->name}\" quedó en {$newStock} {$material->unit} "
                                        . "(mínimo requerido: {$material->stock_minimo} {$material->unit}) "
                                        . "tras registrar avance en la suborden \"{$subOrder->proceso}\".",
                                    'status'                => 'pendiente',
                                    'importance'            => $newStock <= 0 ? 'alta' : 'media',
                                ]);
                            }
                        }
                    }
                }
            }

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
        // CORRECCIÓN: se limpia la tabla pivote antes de borrar, para no dejar
        // registros huérfanos si la migración no tiene ON DELETE CASCADE.
        $subOrder->assignedUsers()->detach();
        $subOrder->delete();

        return redirect()->back()->with('success', 'Suborden eliminada correctamente.');
    }
}