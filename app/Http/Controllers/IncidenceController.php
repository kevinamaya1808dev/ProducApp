<?php

namespace App\Http\Controllers;

use App\Models\Incidence;
use App\Models\IncidenceLog;
use App\Models\ProductionOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IncidenceController extends Controller
{
    public function index(Request $request)
    {
        $query = Incidence::with(['order', 'operario', 'logs.user']);

        // Si el usuario logueado es Operario, solo ve las incidencias asignadas o creadas por él
        if (Auth::user()->role === 'operario') {
            $query->where('user_id', Auth::id());
        }

        // Filtros opcionales
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('importance')) {
            $query->where('importance', $request->importance);
        }

        $incidences = $query->latest()->paginate(10);
        $productionOrders = ProductionOrder::all();

        return view('admin.incidences.index', compact('incidences', 'productionOrders'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'production_order_id' => 'required|exists:production_orders,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'importance' => 'required|in:baja,media,alta',
        ]);

        $incidence = Incidence::create([
            'production_order_id' => $request->production_order_id,
            'user_id' => Auth::id(),
            'title' => $request->title,
            'description' => $request->description,
            'status' => 'pendiente',
            'importance' => $request->importance,
        ]);

        // Registrar Log de creación
        IncidenceLog::create([
            'incidence_id' => $incidence->id,
            'user_id' => Auth::id(),
            'type' => 'creacion',
            'comment' => 'Incidencia creada en estado pendiente con prioridad ' . strtoupper($request->importance),
        ]);

        return redirect()->back()->with('success', 'Incidencia reportada correctamente.');
    }

    public function updateStatus(Request $request, Incidence $incidence)
    {
        $request->validate([
            'status' => 'required|in:pendiente,en_proceso,resuelta',
            'comment' => 'nullable|string'
        ]);

        $prevStatus = $incidence->status;
        $incidence->update(['status' => $request->status]);

        $text = "Estado cambiado de '{$prevStatus}' a '{$request->status}'";
        if ($request->comment) {
            $text .= ". Nota: " . $request->comment;
        }

        IncidenceLog::create([
            'incidence_id' => $incidence->id,
            'user_id' => Auth::id(),
            'type' => 'cambio_estado',
            'comment' => $text,
        ]);

        return redirect()->back()->with('success', 'Estado de incidencia actualizado.');
    }

    public function updateImportance(Request $request, Incidence $incidence)
    {
        if (Auth::user()->role === 'operario') {
            return redirect()->back()->with('error', 'No tienes permisos para modificar la prioridad.');
        }

        $request->validate([
            'importance' => 'required|in:baja,media,alta',
        ]);

        $prevImportance = $incidence->importance;
        $incidence->update(['importance' => $request->importance]);

        IncidenceLog::create([
            'incidence_id' => $incidence->id,
            'user_id' => Auth::id(),
            'type' => 'cambio_prioridad',
            'comment' => "Prioridad modificada de '{$prevImportance}' a '{$request->importance}'",
        ]);

        return redirect()->back()->with('success', 'Prioridad actualizada.');
    }

    public function addNote(Request $request, Incidence $incidence)
    {
        $request->validate([
            'comment' => 'required|string',
        ]);

        IncidenceLog::create([
            'incidence_id' => $incidence->id,
            'user_id' => Auth::id(),
            'type' => 'nota',
            'comment' => $request->comment,
        ]);

        return redirect()->back()->with('success', 'Nota registrada en el historial.');
    }

    public function destroy(Incidence $incidence)
    {
        if (Auth::user()->role === 'operario') {
            return redirect()->back()->with('error', 'Los operarios no pueden eliminar incidencias.');
        }

        $incidence->delete();
        return redirect()->back()->with('success', 'Incidencia eliminada con éxito.');
    }
}