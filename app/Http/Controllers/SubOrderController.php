<?php

namespace App\Http\Controllers;

use App\Models\Incidence;
use App\Models\ProductionSubOrder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class SubOrderController extends Controller
{
    private function subOrderRules(): array
    {
        return [
            'production_order_id' => 'required|exists:production_orders,id',
            'proceso'             => 'required|string|max:100',
            'quantity'            => 'required|integer|min:1',
            'es_ensamblaje'       => 'nullable|boolean',
            'operarios'           => 'nullable|array',
            'operarios.*'         => 'exists:users,id',
            'start_date'          => 'nullable|date',
            'end_date'            => 'nullable|date|after_or_equal:start_date',
            'notas'               => 'nullable|string|max:500',
        ];
    }

    // Construye el array de pivot para asignar operarios con sus datos actuales preservados.
    private function buildSyncData(array $userIds, ProductionSubOrder $subOrder, bool $preservePivot = false): array
    {
        $pivotActual = $preservePivot
            ? $subOrder->assignedUsers()->get()->keyBy('id')
            : collect();

        return collect($userIds)->mapWithKeys(function ($userId) use ($pivotActual) {
            $anterior = $pivotActual->get($userId);

            return [$userId => [
                'estacion'           => $anterior->pivot->estacion           ?? 'General',
                'pieces_contributed' => $anterior->pivot->pieces_contributed ?? 0,
            ]];
        })->toArray();
    }

    // Si esta suborden es de ensamblaje, desmarca cualquier otra de la misma
    // orden para garantizar que solo exista UNA fase de ensamblaje por orden.
    private function garantizarEnsamblaje(ProductionSubOrder $subOrder): void
    {
        if ($subOrder->es_ensamblaje) {
            ProductionSubOrder::where('production_order_id', $subOrder->production_order_id)
                ->where('id', '!=', $subOrder->id)
                ->update(['es_ensamblaje' => false]);
        }
    }

    // Verifica que el usuario tenga derecho a registrar avance en esta suborden.
    private function usuarioPuedeRegistrar(ProductionSubOrder $subOrder, $user): bool
    {
        if ($subOrder->assignedUsers()->where('user_id', $user->id)->exists()) return true;

        $orden = $subOrder->productionOrder;

        return ($orden && $orden->user_id === $user->id) || $user->hasPermission('manage-orders');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate($this->subOrderRules());

        $data['status']       = 'pending';
        $data['es_ensamblaje'] = $request->boolean('es_ensamblaje');

        $operarios = $data['operarios'] ?? [];
        unset($data['operarios']);

        DB::transaction(function () use ($data, $operarios) {
            $subOrder = ProductionSubOrder::create($data);

            if (!empty($operarios)) {
                $subOrder->assignedUsers()->sync($this->buildSyncData($operarios, $subOrder));
            }

            $this->garantizarEnsamblaje($subOrder);
        });

        return back()->with('success', 'Suborden creada correctamente.');
    }

    public function update(Request $request, ProductionSubOrder $subOrder): RedirectResponse
    {
        $data = $request->validate([
            'proceso'          => 'sometimes|required|string|max:100',
            'status'           => 'sometimes|required|in:pending,in_progress,completed,cancelled',
            'quantity'         => 'sometimes|required|integer|min:1',
            'completed_pieces' => 'sometimes|required|integer|min:0',
            'es_ensamblaje'    => 'nullable|boolean',
            'operarios'        => 'nullable|array',
            'operarios.*'      => 'exists:users,id',
            'notas'            => 'nullable|string|max:500',
        ]);

        $data['es_ensamblaje'] = $request->boolean('es_ensamblaje');
        $operarios = $request->has('operarios') ? ($data['operarios'] ?? []) : null;
        unset($data['operarios']);

        DB::transaction(function () use ($subOrder, $data, $operarios) {
            $subOrder->update($data);

            if ($operarios !== null) {
                $subOrder->assignedUsers()->sync($this->buildSyncData($operarios, $subOrder, true));
            }

            $this->garantizarEnsamblaje($subOrder);
        });

        return back()->with('success', 'Suborden actualizada correctamente.');
    }

    public function registerProgress(Request $request, ProductionSubOrder $subOrder): RedirectResponse
    {
        $request->validate(['quantity_produced' => 'required|integer|min:1']);

        $user = auth()->user();

        if (!$this->usuarioPuedeRegistrar($subOrder, $user)) {
            abort(403, 'No estás asignado a esta suborden.');
        }

        $piecesProduced = $request->input('quantity_produced');

        DB::transaction(function () use ($subOrder, $user, $piecesProduced) {
            $subOrder = ProductionSubOrder::whereKey($subOrder->id)->lockForUpdate()->firstOrFail();

            $restantes = max(0, $subOrder->quantity - $subOrder->completed_pieces);

            if ($piecesProduced > $restantes) {
                throw ValidationException::withMessages([
                    'quantity_produced' => $restantes > 0
                        ? "Solo faltan {$restantes} piezas para esta fase; no puedes registrar {$piecesProduced}."
                        : 'Esta fase ya está completa; no se puede registrar más avance.',
                ]);
            }

            // Aportación individual (tabla pivote)
            $pivot = $subOrder->assignedUsers()->where('user_id', $user->id)->first();

            if ($pivot) {
                $subOrder->assignedUsers()->updateExistingPivot($user->id, [
                    'pieces_contributed' => $pivot->pivot->pieces_contributed + $piecesProduced,
                ]);
            } else {
                $subOrder->assignedUsers()->attach($user->id, [
                    'estacion'           => 'General',
                    'pieces_contributed' => $piecesProduced,
                ]);
            }

            $subOrder->increment('completed_pieces', $piecesProduced);

            if ($subOrder->completed_pieces >= $subOrder->quantity) {
                $subOrder->update(['status' => 'completed']);
            } elseif ($subOrder->status === 'pending') {
                $subOrder->update(['status' => 'in_progress']);
            }

            $orden = $subOrder->productionOrder()->lockForUpdate()->first();

            $avanzaLaOrden = $orden && ($subOrder->es_ensamblaje || $orden->subOrders()->count() <= 1);

            if ($avanzaLaOrden) {
                foreach ($orden->product->recipes ?? [] as $recipe) {
                    $material = $recipe->material()->lockForUpdate()->first();

                    if (!$material) continue;

                    $newStock = max(0, $material->stock_actual - $recipe->quantity_required * $piecesProduced);
                    $material->update(['stock_actual' => $newStock]);

                    if ($newStock <= $material->stock_minimo) {
                        $titulo = "Stock bajo: {$material->name} ({$material->sku})";

                        if (!Incidence::where('title', $titulo)->whereIn('status', ['pendiente', 'en_proceso'])->exists()) {
                            Incidence::create([
                                'production_order_id' => $subOrder->production_order_id,
                                'user_id'             => $user->id,
                                'title'               => $titulo,
                                'description'         => "El material \"{$material->name}\" quedó en {$newStock} {$material->unit} "
                                    . "(mínimo: {$material->stock_minimo} {$material->unit}) "
                                    . "tras avanzar en \"{$subOrder->proceso}\".",
                                'status'              => 'pendiente',
                                'importance'          => $newStock <= 0 ? 'alta' : 'media',
                            ]);
                        }
                    }
                }

                if ($subOrder->es_ensamblaje) {
                    $orden->product()->increment('stock', $piecesProduced);
                }

                $orden->increment('completed_pieces', $piecesProduced);
                $orden->status = $orden->completed_pieces >= $orden->quantity
                    ? 'completed'
                    : (strtolower($orden->status) === 'pending' ? 'in_progress' : $orden->status);
                $orden->save();

            } elseif ($orden && strtolower($orden->status) === 'pending') {
                $orden->update(['status' => 'in_progress']);
            }
        });

        return back()->with('success', '¡Avance registrado y stock descontado del almacén exitosamente!');
    }

    public function destroy(ProductionSubOrder $subOrder): RedirectResponse
    {
        $subOrder->assignedUsers()->detach();
        $subOrder->delete();

        return back()->with('success', 'Suborden eliminada correctamente.');
    }
}