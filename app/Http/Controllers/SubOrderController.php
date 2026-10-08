<?php

namespace App\Http\Controllers;

use App\Models\RegistroProduccion;
use App\Models\ProductionSubOrder;
use App\Rules\UsuarioAsignable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
            'operarios.*'         => ['exists:users,id', new UsuarioAsignable()],
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
            'status'           => 'sometimes|required|in:pending,in_progress,completed',
            'quantity'         => 'sometimes|required|integer|min:1',
            'completed_pieces' => 'sometimes|required|integer|min:0',
            'es_ensamblaje'    => 'nullable|boolean',
            'operarios'        => 'nullable|array',
            'operarios.*'      => ['exists:users,id', new UsuarioAsignable()],
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

    public function destroy(ProductionSubOrder $subOrder): RedirectResponse
    {
        // Con avance registrado, borrarla dejaría sus registros huérfanos
        // (sub_order_id = null) y se contarían como avance de la orden completa.
        if ($subOrder->completed_pieces > 0 || RegistroProduccion::where('sub_order_id', $subOrder->id)->exists()) {
            return back()->with('error',
                "No se puede eliminar la suborden '{$subOrder->proceso}': ya tiene avance registrado.");
        }

        $subOrder->assignedUsers()->detach();
        $subOrder->delete();

        return back()->with('success', 'Suborden eliminada correctamente.');
    }
}