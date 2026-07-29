<?php

namespace App\Http\Controllers;

use App\Models\Incidence;
use App\Models\ProductionOrder;
use App\Models\RegistroProduccion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OperarioController extends Controller
{
    public function inicio()
    {
        $userId = Auth::id();

        // Orden de producción activa asignada al operario
        $ordenActiva = ProductionOrder::with('product')
            ->where('user_id', $userId)
            ->where('status', 'in_progress')
            ->latest()
            ->first();

        // Total de piezas hechas hoy por el operario (todas las órdenes)
        $piezasHoy = RegistroProduccion::where('user_id', $userId)
            ->whereDate('created_at', today())
            ->sum('cantidad');

        // Piezas registradas hoy específicamente para la orden activa
        $piezasOrdenActiva = 0;
        if ($ordenActiva) {
            $piezasOrdenActiva = RegistroProduccion::where('production_order_id', $ordenActiva->id)
                ->whereDate('created_at', today())
                ->sum('cantidad');
        }

        $actividadesRecientes = RegistroProduccion::where('user_id', $userId)
            ->latest()
            ->take(5)
            ->get();

        return view('operario.inicio', compact('ordenActiva', 'piezasHoy', 'piezasOrdenActiva', 'actividadesRecientes'));
    }

    public function registro()
{
    $userId = Auth::id();

    $ordenActiva = ProductionOrder::with('product')
        ->where('user_id', $userId)
        ->where('status', 'in_progress')
        ->latest()
        ->first();

    $piezasOrdenActiva = 0;
    if ($ordenActiva) {
        $piezasOrdenActiva = RegistroProduccion::where('production_order_id', $ordenActiva->id)
            ->whereDate('created_at', today())
            ->sum('cantidad');
    }

    $tarea = $ordenActiva ? [
        'titulo' => $ordenActiva->product->name ?? 'Sin producto',
        'descripcion' => $ordenActiva->product->description ?? $ordenActiva->order_number,
        'actual' => $piezasOrdenActiva,
        'total' => $ordenActiva->quantity,
    ] : null;

    $registrosHoy = RegistroProduccion::where('user_id', $userId)
        ->whereDate('created_at', today())
        ->oldest()
        ->get();

    $registros = $registrosHoy->values()->map(function ($reg, $index) {
        $esUnidad = $reg->cantidad == 1;

        return [
            'numero' => str_pad($index + 1, 3, '0', STR_PAD_LEFT),
            'hora' => $reg->created_at->format('H:i'),
            'cantidad' => $reg->cantidad,
            'tipo' => $esUnidad ? '+1 Unidad' : '+Lote',
            'tipo_clase' => $esUnidad
                ? 'bg-blue-50 text-blue-600 border border-blue-100'
                : 'bg-slate-100 text-slate-600',
            'nota' => '—',
        ];
    })->reverse()->values();

    return view('operario.registro', [
        'tarea' => $tarea,
        'ordenId' => $ordenActiva->id ?? null,
        'routeGuardar' => route('operario.registro.guardar'),
        'registros' => $registros,
    ]);
}

public function guardarRegistro(Request $request)
{
    $request->validate([
        'production_order_id' => 'required|exists:production_orders,id',
        'cantidad' => 'required|integer|min:1',
    ]);

    RegistroProduccion::create([
        'user_id' => Auth::id(),
        'production_order_id' => $request->production_order_id,
        'cantidad' => $request->cantidad,
        'fecha_registro' => now(),
    ]);

    return redirect()->back()->with('success', '¡Producción registrada correctamente!');
}

    public function perfil()
{
    $user = Auth::user();

    $ultimaOrden = ProductionOrder::where('user_id', $user->id)
        ->whereNotNull('estacion')
        ->latest()
        ->first();

    $usuario = [
        'iniciales' => collect(explode(' ', $user->name))
            ->map(fn($p) => mb_strtoupper(mb_substr($p, 0, 1)))
            ->take(2)->implode(''),
        'nombre' => $user->name,
        'puesto' => $user->puesto ?? 'Operario',
        'estado' => $user->active ? 'Activo' : 'Inactivo',
        'id_operario' => 'OP-' . str_pad($user->id, 3, '0', STR_PAD_LEFT),
        'estacion' => $ultimaOrden->estacion ?? 'Sin asignar',
        'turno' => $user->turno ?? 'Sin definir',
        'planta' => $user->planta ?? 'Sin definir',
        'alta_desde' => $user->created_at->translatedFormat('M Y'),
    ];

    $habilidades = $user->skills->pluck('skill')->toArray();

    $certificaciones = $user->certifications->map(fn($cert) => [
        'nombre' => $cert->nombre,
        'fecha' => $cert->fecha_obtencion?->translatedFormat('M Y') ?? '—',
    ])->toArray();

    $desde = now()->subDays(30);

    $ordenesCompletas = ProductionOrder::where('user_id', $user->id)
        ->where('status', 'completed')
        ->count();

    $incidenciasRecientes = Incidence::where('user_id', $user->id)
        ->where('created_at', '>=', $desde)
        ->count();

    $ordenesCompletadasRecientes = ProductionOrder::with('registros')
        ->where('user_id', $user->id)
        ->where('status', 'completed')
        ->where('updated_at', '>=', $desde)
        ->get();

    $eficiencia = $ordenesCompletadasRecientes->isNotEmpty()
        ? round($ordenesCompletadasRecientes->avg(fn($o) => $o->porcentaje_avance)) . '%'
        : '0%';

    $metaDiaria = $user->meta_diaria ?? 100;

    $inicioSemana = now()->startOfWeek(\Carbon\Carbon::MONDAY);
    $produccionSemana = [];
    for ($i = 0; $i < 5; $i++) {
        $dia = $inicioSemana->copy()->addDays($i);
        $piezas = RegistroProduccion::where('user_id', $user->id)
            ->whereDate('created_at', $dia)
            ->sum('cantidad');

        $produccionSemana[] = [
            'label' => $dia->translatedFormat('D'),
            'piezas' => $piezas,
            'porcentaje' => $metaDiaria > 0 ? min(($piezas / $metaDiaria) * 100, 100) : 0,
            'cumplida' => $piezas >= $metaDiaria,
        ];
    }

    $historial = ProductionOrder::with('product')
        ->where('user_id', $user->id)
        ->where('status', 'completed')
        ->latest('updated_at')
        ->take(10)
        ->get()
        ->map(fn($orden) => [
            'orden' => $orden->order_number,
            'producto' => $orden->product->name ?? 'Sin producto',
            'fecha' => optional($orden->updated_at)->translatedFormat('d M Y'),
            'unidades' => $orden->quantity,
            'eficiencia' => round($orden->porcentaje_avance),
        ]);

    return view('operario.perfil', [
        'usuario' => $usuario,
        'habilidades' => $habilidades,
        'certificaciones' => $certificaciones,
        'eficiencia' => $eficiencia,
        'ordenesCompletas' => $ordenesCompletas,
        'incidencias' => $incidenciasRecientes,
        'metaDiaria' => $metaDiaria,
        'produccionSemana' => $produccionSemana,
        'rangoFechas' => 'Semana del ' . $inicioSemana->format('d') . ' al ' . $inicioSemana->copy()->addDays(4)->translatedFormat('d M Y'),
        'historial' => $historial,
    ]);
}


    // ---------------------------------------------------------
    // MÉTODOS DE INCIDENCIAS (NUEVOS FRAGMENTOS)
    // ---------------------------------------------------------

    public function incidencias(Request $request)
{
    $userId = Auth::id();

    $incidencias = Incidence::with('order')
        ->where('user_id', $userId)
        ->latest()
        ->get();

    $ordenes = ProductionOrder::where('user_id', $userId)->latest()->get();

    $mostrarFormulario = $request->boolean('nueva');
    $incidenciaSeleccionada = null;

    if (!$mostrarFormulario) {
        $incidenciaSeleccionada = $request->filled('incidencia')
            ? $incidencias->firstWhere('id', (int) $request->query('incidencia'))
            : $incidencias->first();
    }

    return view('operario.incidencias', compact('incidencias', 'ordenes', 'incidenciaSeleccionada', 'mostrarFormulario'));
}

public function crearIncidencia(Request $request)
{
    $request->validate([
        'production_order_id' => 'required|exists:production_orders,id',
        'title' => 'required|string|max:255',
        'description' => 'required|string',
    ]);

    $incidencia = Incidence::create([
        'production_order_id' => $request->production_order_id,
        'user_id' => Auth::id(),
        'title' => $request->title,
        'description' => $request->description,
    ]);

    return redirect()->route('operario.incidencias', ['incidencia' => $incidencia->id])
        ->with('success', 'Incidencia reportada correctamente.');
}

    // ---------------------------------------------------------
    // MÉTODOS DE TAREAS Y ESTACIONES
    // ---------------------------------------------------------

    public function actualizarEstacion(Request $request, ProductionOrder $productionOrder)
    {
        // Seguridad: solo el operario asignado puede modificar su propia orden
        if ($productionOrder->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'estacion' => 'required|string|max:50',
        ]);

        $productionOrder->update(['estacion' => $request->estacion]);

        return redirect()->route('operario.inicio')->with('success', 'Estación asignada correctamente.');
    }

    public function tareas(Request $request)
    {
        $ordenes = ProductionOrder::with('product')
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        $ordenSeleccionada = $request->filled('orden')
            ? $ordenes->firstWhere('id', (int) $request->query('orden'))
            : $ordenes->first();

        return view('operario.tareas', compact('ordenes', 'ordenSeleccionada'));
    }

    public function iniciarTarea(ProductionOrder $productionOrder)
    {
        if ($productionOrder->user_id !== Auth::id()) {
            abort(403);
        }

        $productionOrder->update(['status' => 'in_progress']);

        return redirect()->route('operario.tareas', ['orden' => $productionOrder->id])
            ->with('success', 'Tarea iniciada.');
    }

    public function completarTarea(ProductionOrder $productionOrder)
    {
        if ($productionOrder->user_id !== Auth::id()) {
            abort(403);
        }

        $productionOrder->update(['status' => 'completed']);

        return redirect()->route('operario.tareas', ['orden' => $productionOrder->id])
            ->with('success', 'Tarea marcada como completada.');
    }
}