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
        $user = Auth::user();

        // Busca primero una orden en progreso; si no existe, toma la pendiente más reciente
        $ordenActiva = ProductionOrder::with('product')
            ->where('user_id', $userId)
            ->whereIn('status', ['in_progress', 'pending', 'Pending'])
            ->orderByRaw("CASE WHEN LOWER(status) = 'in_progress' THEN 1 ELSE 2 END")
            ->latest()
            ->first();

        // Total de piezas hechas hoy por el operario (todas las órdenes)
        $piezasHoy = RegistroProduccion::where('user_id', $userId)
            ->whereDate('created_at', today())
            ->sum('cantidad');

        // Piezas registradas para la orden activa (acumulado total de la orden para este usuario)
$piezasOrdenActiva = 0;
if ($ordenActiva) {
    $piezasOrdenActiva = RegistroProduccion::where('production_order_id', $ordenActiva->id)
        ->where('user_id', $userId)
        ->sum('cantidad');
}

        // Incidencias reportadas hoy por el operario
        $incidenciasHoy = Incidence::where('user_id', $userId)
            ->whereDate('created_at', today())
            ->count();

        // Cálculo de eficiencia en base a la meta diaria asignada (por defecto 100 piezas)
        $metaDiaria = $user->meta_diaria ?? 100;
        $eficiencia = $metaDiaria > 0 
            ? min(round(($piezasHoy / $metaDiaria) * 100), 100) 
            : 0;

        $actividadesRecientes = RegistroProduccion::where('user_id', $userId)
            ->latest()
            ->take(5)
            ->get();

        return view('operario.inicio', compact(
            'ordenActiva', 
            'piezasHoy', 
            'piezasOrdenActiva', 
            'incidenciasHoy',
            'eficiencia',
            'actividadesRecientes'
        ));
    }

    public function registro()
    {
        $userId = Auth::id();

        $ordenActiva = ProductionOrder::with('product')
            ->where('user_id', $userId)
            ->where('status', 'in_progress')
            ->latest()
            ->first();

        // Piezas registradas para la orden activa (acumulado total de la orden para este usuario)
$piezasOrdenActiva = 0;
if ($ordenActiva) {
    $piezasOrdenActiva = RegistroProduccion::where('production_order_id', $ordenActiva->id)
        ->where('user_id', $userId)
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
            $esNotaSinCantidad = $reg->cantidad == 0 && !empty($reg->nota);

            if ($esNotaSinCantidad) {
                $tipo = 'Nota';
                $tipoClase = 'bg-amber-50 text-amber-700 border border-amber-200';
            } elseif ($esUnidad) {
                $tipo = '+1 Unidad';
                $tipoClase = 'bg-blue-50 text-blue-600 border border-blue-100';
            } else {
                $tipo = '+Lote';
                $tipoClase = 'bg-slate-100 text-slate-600';
            }

            return [
                'numero' => str_pad($index + 1, 3, '0', STR_PAD_LEFT),
                'hora' => $reg->created_at->format('H:i'),
                'cantidad' => $reg->cantidad,
                'tipo' => $tipo,
                'tipo_clase' => $tipoClase,
                'nota' => $reg->nota ?? '—',
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
            'cantidad' => 'nullable|integer|min:0',
            'nota' => 'nullable|string|max:255',
        ]);

        if (($request->cantidad === null || $request->cantidad == 0) && empty($request->nota)) {
            return redirect()->back()->withErrors(['error' => 'Debes ingresar una cantidad o una nota.']);
        }

        // Validar límite máximo de la orden si se envía una cantidad mayor a 0
        if ($request->filled('cantidad') && $request->cantidad > 0) {
            $orden = ProductionOrder::findOrFail($request->production_order_id);
            
            // Sumar piezas previamente registradas por este usuario en esta orden
            $piezasRegistradas = RegistroProduccion::where('production_order_id', $orden->id)
                ->where('user_id', Auth::id())
                ->sum('cantidad');

            $maxPermitido = max(0, $orden->quantity - $piezasRegistradas);

            if ($request->cantidad > $maxPermitido) {
                return redirect()->back()->withErrors(['cantidad' => 'Esa cantidad no puede ser ingresada']);
            }
        }

        $registroDuplicado = RegistroProduccion::where('user_id', Auth::id())
            ->where('production_order_id', $request->production_order_id)
            ->where('cantidad', $request->cantidad ?? 0)
            ->where('nota', $request->nota)
            ->where('created_at', '>=', now()->subSeconds(5))
            ->exists();

        if ($registroDuplicado) {
            return redirect()->back()->with('warning', 'Este registro ya se guardó hace unos segundos, evitamos duplicarlo.');
        }

        RegistroProduccion::create([
            'user_id' => Auth::id(),
            'production_order_id' => $request->production_order_id,
            'cantidad' => $request->cantidad ?? 0,
            'nota' => $request->nota,
            'fecha_registro' => now(),
        ]);

        return redirect()->back()->with('success', '¡Registro guardado correctamente!');
    }

    public function perfil()
    {
        // Obtenemos los datos frescos del usuario directo de la BD junto a sus relaciones
        $user = \App\Models\User::with(['skills', 'certifications'])->find(Auth::id());

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
            
            // LA MAGIA ESTÁ AQUÍ: 
            // Ahora leemos la columna 'planta' de la base de datos, 
            // pero la mandamos a la vista con el nombre 'estacion'
            'estacion' => $user->planta ?? $ultimaOrden->estacion ?? 'Sin asignar',
            
            'turno' => $user->turno ?? 'Sin definir',
            'alta_desde' => optional($user->created_at)->translatedFormat('M Y') ?? '—',
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
            // Ocultar si la fecha de vencimiento ya pasó hace más de 1 mes, 
            // o mostrar solo las terminadas a tiempo / vigentes en el último mes
            ->where(function ($query) {
                $query->whereNull('end_date')
                      ->orWhere('end_date', '>=', now()->subMonth());
            })
            ->latest('updated_at')
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

    public function incidencias(Request $request)
    {
        $userId = Auth::id();

        $incidencias = Incidence::with('order')
            ->where('user_id', $userId)
            ->orderByRaw("CASE WHEN importance = 'alta' THEN 1 WHEN importance = 'media' THEN 2 ELSE 3 END")
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
            'importance' => 'required|in:baja,media,alta',
        ]);

        $incidencia = Incidence::create([
            'production_order_id' => $request->production_order_id,
            'user_id' => Auth::id(),
            'title' => $request->title,
            'description' => $request->description,
            'importance' => $request->importance,
        ]);

        return redirect()->route('operario.incidencias', ['incidencia' => $incidencia->id])
            ->with('success', 'Incidencia reportada correctamente.');
    }

    public function actualizarEstacion(Request $request, ProductionOrder $productionOrder)
    {
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