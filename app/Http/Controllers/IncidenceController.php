<?php

namespace App\Http\Controllers;

use App\Models\Incidence;
use App\Models\IncidenceLog;
use App\Models\ProductionOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IncidenceController extends Controller
{
    private function log(Incidence $incidence, string $type, string $comment): void
    {
        IncidenceLog::create([
            'incidence_id' => $incidence->id,
            'user_id'      => Auth::id(),
            'type'         => $type,
            'comment'      => $comment,
        ]);
    }

    // CORRECCIÓN: ->role no existe como columna en users; usar hasRole()
    // que consulta la relación real roles() del modelo User.
    private function esOperario(): bool
    {
        return Auth::user()->hasRole('operario');
    }

    public function index(Request $request)
    {
        $incidences = Incidence::with(['order', 'operario', 'logs.user'])
            ->when($this->esOperario(),  fn ($q) => $q->where('user_id', Auth::id()))
            ->when($request->status,     fn ($q, $v) => $q->where('status', $v))
            ->when($request->importance, fn ($q, $v) => $q->where('importance', $v))
            ->latest()->paginate(10);

        return view('admin.incidences.index', [
            'incidences'       => $incidences,
            'productionOrders' => ProductionOrder::all(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'production_order_id' => 'required|exists:production_orders,id',
            'title'               => 'required|string|max:255',
            'description'         => 'required|string',
            'importance'          => 'required|in:baja,media,alta',
        ]);

        $incidence = Incidence::create($data + ['user_id' => Auth::id(), 'status' => 'pendiente']);

        $this->log($incidence, 'creacion', 'Incidencia creada en estado pendiente con prioridad ' . strtoupper($data['importance']));

        return back()->with('success', 'Incidencia reportada correctamente.');
    }

    public function updateStatus(Request $request, Incidence $incidence)
    {
        $data = $request->validate([
            'status'  => 'required|in:pendiente,en_proceso,resuelta',
            'comment' => 'nullable|string',
        ]);

        $prev = $incidence->status;
        $incidence->update(['status' => $data['status']]);

        $comment = "Estado cambiado de '{$prev}' a '{$data['status']}'";
        if ($data['comment']) $comment .= '. Nota: ' . $data['comment'];

        $this->log($incidence, 'cambio_estado', $comment);

        return back()->with('success', 'Estado de incidencia actualizado.');
    }

    public function updateImportance(Request $request, Incidence $incidence)
    {
        if ($this->esOperario()) {
            return back()->with('error', 'No tienes permisos para modificar la prioridad.');
        }

        $data = $request->validate(['importance' => 'required|in:baja,media,alta']);
        $prev = $incidence->importance;
        $incidence->update($data);

        $this->log($incidence, 'cambio_prioridad', "Prioridad modificada de '{$prev}' a '{$data['importance']}'");

        return back()->with('success', 'Prioridad actualizada.');
    }

    public function addNote(Request $request, Incidence $incidence)
    {
        $data = $request->validate(['comment' => 'required|string']);

        $this->log($incidence, 'nota', $data['comment']);

        return back()->with('success', 'Nota registrada en el historial.');
    }

    public function destroy(Incidence $incidence)
    {
        if ($this->esOperario()) {
            return back()->with('error', 'Los operarios no pueden eliminar incidencias.');
        }

        $incidence->delete();

        return back()->with('success', 'Incidencia eliminada con éxito.');
    }
}