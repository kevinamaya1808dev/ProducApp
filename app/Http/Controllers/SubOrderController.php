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

            // NUEVO: solo puede existir UNA fase de ensamblaje por orden. Si
            // esta suborden quedó marcada como tal, se desmarca cualquier
            // otra de la misma orden para no duplicar el avance del padre
            // (mismo bug de doble conteo que corregimos antes, pero ahora
            // a prueba de que se marquen dos subórdenes en momentos distintos).
            if ($subOrder->es_ensamblaje) {
                ProductionSubOrder::where('production_order_id', $subOrder->production_order_id)
                    ->where('id', '!=', $subOrder->id)
                    ->update(['es_ensamblaje' => false]);
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

            // NUEVO: misma regla que en store() — al editar, si esta suborden
            // queda marcada como ensamblaje, se desmarca cualquier otra de la
            // misma orden.
            if ($subOrder->es_ensamblaje) {
                ProductionSubOrder::where('production_order_id', $subOrder->production_order_id)
                    ->where('id', '!=', $subOrder->id)
                    ->update(['es_ensamblaje' => false]);
            }
        });

        return redirect()->back()->with('success', 'Suborden actualizada correctamente.');
    }

    /**
     * Comprueba si el usuario autenticado tiene derecho a registrar avance
     * sobre esta suborden: porque está asignado a ella, porque es dueño de
     * la orden padre, o porque tiene permiso de gestión (módulo híbrido).
     *
     * CORRECCIÓN DE SEGURIDAD: antes cualquier usuario con el permiso
     * genérico "update-progress" podía llamar a registerProgress() sobre
     * CUALQUIER {subOrder} solo cambiando el ID en la URL, sin estar
     * asignado a esa suborden (IDOR). La ruta ya exige el permiso, pero
     * el permiso por sí solo no prueba pertenencia; ahora se valida aquí.
     */
    private function usuarioPuedeRegistrarEnSuborden(ProductionSubOrder $subOrder, $user): bool
    {
        if ($subOrder->assignedUsers()->where('user_id', $user->id)->exists()) {
            return true;
        }

        $orden = $subOrder->productionOrder;

        if ($orden && $orden->user_id === $user->id) {
            return true;
        }

        return $user->hasPermission('manage-orders');
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

        // CORRECCIÓN DE SEGURIDAD: cortamos aquí, antes de abrir la
        // transacción, si el usuario no tiene ninguna relación real con
        // esta suborden.
        if (!$this->usuarioPuedeRegistrarEnSuborden($subOrder, $user)) {
            abort(403, 'No estás asignado a esta suborden.');
        }

        $piecesProduced = $request->input('quantity_produced');

        DB::transaction(function () use ($subOrder, $user, $piecesProduced) {

            // CORRECCIÓN DE CONCURRENCIA: volvemos a leer la suborden con
            // lockForUpdate() dentro de la transacción. Antes se trabajaba
            // con la instancia resuelta por el route-model-binding (fuera de
            // cualquier bloqueo); si dos operarios registraban avance sobre
            // la misma suborden casi al mismo tiempo, ambas peticiones podían
            // leer "quantity"/"completed_pieces" desde memoria antes de que
            // la otra terminara de escribir, produciendo lecturas obsoletas
            // para las comparaciones de estado/tope que siguen abajo.
            $subOrder = ProductionSubOrder::whereKey($subOrder->id)->lockForUpdate()->firstOrFail();

            // CORRECCIÓN: se limita quantity_produced a lo que realmente
            // falta en esta fase. Antes no existía ningún tope: un operario
            // podía reportar más piezas de las que tenía pendientes la
            // suborden, dejando completed_pieces por encima de quantity y
            // descontando del almacén material que en realidad no se usó.
            $restantes = max(0, $subOrder->quantity - $subOrder->completed_pieces);

            if ($piecesProduced > $restantes) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'quantity_produced' => $restantes > 0
                        ? "Solo faltan {$restantes} piezas para esta fase; no puedes registrar {$piecesProduced}."
                        : 'Esta fase ya está completa; no se puede registrar más avance.',
                ]);
            }

            // 1) Aportación individual del operario (tabla pivote)
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

            // CORRECCIÓN: este método nunca actualizaba las piezas propias de
            // la suborden ni su estado — solo la tabla pivote. Una suborden
            // podía quedarse en "pending"/"in_progress" para siempre aunque
            // ya se hubiera producido toda su cantidad.
            $subOrder->increment('completed_pieces', $piecesProduced);

            if ($subOrder->completed_pieces >= $subOrder->quantity) {
                $subOrder->update(['status' => 'completed']);
            } elseif ($subOrder->status === 'pending') {
                $subOrder->update(['status' => 'in_progress']);
            }

            // CORRECCIÓN DE CONCURRENCIA: la orden padre también se bloquea
            // antes de leer/incrementar sus totales, por la misma razón que
            // la suborden arriba.
            $orden = $subOrder->productionOrder()->lockForUpdate()->first();

            // CORRECCIÓN: antes se descontaban materiales por CADA fase sin
            // distinción, y este método nunca tocaba la orden padre. Ahora la
            // orden padre (avance, estado, descuento de materiales, stock del
            // producto) solo se mueve cuando esta suborden es la fase final
            // de ensamblaje, o si es la única suborden de la orden — igual
            // que en OperarioController::guardarRegistro().
            $esFaseQueAvanzaLaOrden = $orden && ($subOrder->es_ensamblaje || $orden->subOrders()->count() <= 1);

            if ($esFaseQueAvanzaLaOrden) {
                $product = $orden->product;

                if ($product && $product->recipes) {
                    foreach ($product->recipes as $recipe) {
                        // CORRECCIÓN DE CONCURRENCIA: se bloquea la fila del
                        // material antes de leer/restar su stock, para que
                        // dos registros simultáneos que consumen el mismo
                        // material no lean el mismo stock_actual de partida.
                        $material = $recipe->material()->lockForUpdate()->first();

                        if ($material) {
                            $totalQuantityNeeded = $recipe->quantity_required * $piecesProduced;
                            $newStock = max(0, $material->stock_actual - $totalQuantityNeeded);

                            $material->update([
                                'stock_actual' => $newStock
                            ]);

                            // Incidencia automática de stock bajo. production_order_id
                            // es obligatorio en la tabla incidences, así que se toma
                            // de la orden a la que pertenece esta suborden.
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

                if ($subOrder->es_ensamblaje) {
                    $orden->product()->increment('stock', $piecesProduced);
                }

                $orden->increment('completed_pieces', $piecesProduced);

                if ($orden->completed_pieces >= $orden->quantity) {
                    $orden->status = 'completed';
                } elseif (strtolower($orden->status) === 'pending') {
                    $orden->status = 'in_progress';
                }

                $orden->save();
            } elseif ($orden && strtolower($orden->status) === 'pending') {
                // Avanzó una fase intermedia: al menos reflejamos que ya hay
                // trabajo en curso, sin tocar completed_pieces ni materiales.
                $orden->status = 'in_progress';
                $orden->save();
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
//optimizado