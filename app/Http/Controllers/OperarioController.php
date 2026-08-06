<?php

namespace App\Http\Controllers;

use App\Models\Incidence;
use App\Models\ProductionOrder;
use App\Models\ProductionSubOrder;
use App\Models\RegistroProduccion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OperarioController extends Controller
{
    // Estados que consideramos "orden con trabajo pendiente/activo" —
    // antes inicio() y registro() usaban criterios distintos y eso rompía el flujo.
    private const ESTADOS_ACTIVOS = ['in_progress', 'pending', 'Pending'];

    private function buscarOrdenActiva(int $userId): ?ProductionOrder
    {
        return ProductionOrder::with(['product', 'subOrders.assignedUsers'])
            ->where(function ($query) use ($userId) {
                $query->where('user_id', $userId)
                      ->orWhereHas('subOrders.assignedUsers', function ($q) use ($userId) {
                          $q->where('users.id', $userId);
                      });
            })
            ->whereIn('status', self::ESTADOS_ACTIVOS)
            ->orderByRaw("CASE WHEN LOWER(status) = 'in_progress' THEN 1 ELSE 2 END")
            ->latest()
            ->first();
    }

    private function buscarSubOrdenDelUsuario(ProductionOrder $orden, int $userId): ?ProductionSubOrder
    {
        return $orden->subOrders()
            ->where(function ($q) use ($userId) {
                $q->where('user_id', $userId)
                  ->orWhereHas('assignedUsers', function ($subQ) use ($userId) {
                      $subQ->where('users.id', $userId);
                  });
            })
            ->first();
    }

    public function inicio()
    {
        $userId = Auth::id();
        $user = Auth::user();

        $ordenActiva = $this->buscarOrdenActiva($userId);

        $piezasHoy = RegistroProduccion::where('user_id', $userId)
            ->whereDate('created_at', today())
            ->sum('cantidad');

        $piezasOrdenActiva = 0;
        $alertaCercana = false;
        $restantes = 0;
        $colegasInvolucrados = collect();
        $subOrdenActiva = null;

        if ($ordenActiva) {
            $piezasOrdenActiva = RegistroProduccion::where('production_order_id', $ordenActiva->id)
                ->where('user_id', $userId)
                ->sum('cantidad');

            $subOrdenActiva = $this->buscarSubOrdenDelUsuario($ordenActiva, $userId);

            if ($subOrdenActiva) {
                $restantes = $subOrdenActiva->restantes;
                $alertaCercana = $subOrdenActiva->alerta_cercana;

                $colegasInvolucrados = $subOrdenActiva->assignedUsers()
                    ->where('users.id', '!=', $userId)
                    ->get();
            }
        }

        $incidenciasHoy = Incidence::where('user_id', $userId)
            ->whereDate('created_at', today())
            ->count();

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
            'subOrdenActiva',
            'restantes',
            'alertaCercana',
            'colegasInvolucrados',
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

        // Antes: ->where('status', 'in_progress') -> si la orden seguía en "pending"
        // el operario no encontraba nada que registrar, aunque sí tuviera trabajo asignado.
        $ordenActiva = $this->buscarOrdenActiva($userId);

        $piezasOrdenActiva = 0;
        $subOrdenActiva = null;
        if ($ordenActiva) {
            $piezasOrdenActiva = RegistroProduccion::where('production_order_id', $ordenActiva->id)
                ->where('user_id', $userId)
                ->sum('cantidad');

            $subOrdenActiva = $this->buscarSubOrdenDelUsuario($ordenActiva, $userId);
        }

        $tarea = $ordenActiva ? [
            'titulo' => $subOrdenActiva ? "{$ordenActiva->product->name} - {$subOrdenActiva->proceso}" : ($ordenActiva->product->name ?? 'Sin producto'),
            'descripcion' => $ordenActiva->product->description ?? $ordenActiva->order_number,
            'actual' => $subOrdenActiva ? $subOrdenActiva->completed_pieces : $piezasOrdenActiva,
            'total' => $subOrdenActiva ? $subOrdenActiva->quantity : $ordenActiva->quantity,
            'sub_order_id' => $subOrdenActiva?->id,
            'restantes' => $subOrdenActiva?->restantes ?? 0,
            'alerta_cercana' => $subOrdenActiva?->alerta_cercana ?? false,
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
            'subOrdenId' => $subOrdenActiva?->id ?? null,
            'routeGuardar' => route('operario.registro.guardar'),
            'routeEstadoSuborden' => $subOrdenActiva ? route('operario.suborden.estado', $subOrdenActiva->id) : null,
            'registros' => $registros,
        ]);
    }

    public function guardarRegistro(Request $request)
    {
        $request->validate([
            'production_order_id' => 'required|exists:production_orders,id',
            'sub_order_id'        => 'nullable|exists:production_sub_orders,id',
            'cantidad'            => 'nullable|integer|min:0',
            'nota'                => 'nullable|string|max:255',
        ]);

        $userId = Auth::id();
        $cantidad = $request->cantidad ?? 0;

        DB::transaction(function () use ($request, $userId, $cantidad) {
            RegistroProduccion::create([
                'user_id'             => $userId,
                'production_order_id' => $request->production_order_id,
                'sub_order_id'        => $request->sub_order_id,
                'cantidad'            => $cantidad,
                'nota'                => $request->nota,
                'fecha_registro'      => now(),
            ]);

            $orden = ProductionOrder::findOrFail($request->production_order_id);

            if ($request->filled('sub_order_id')) {
                $subOrder = ProductionSubOrder::findOrFail($request->sub_order_id);
                $subOrder->increment('completed_pieces', $cantidad);

                if ($subOrder->completed_pieces >= $subOrder->quantity) {
                    $subOrder->update(['status' => 'completed']);
                } elseif ($cantidad > 0) {
                    $subOrder->update(['status' => 'in_progress']);
                }

                $estacionActual = Auth::user()->estacion ?? Auth::user()->planta ?? 'General';
                $existsPivot = $subOrder->assignedUsers()->where('user_id', $userId)->exists();

                if ($existsPivot) {
                    $currentContributed = $subOrder->assignedUsers()->where('user_id', $userId)->first()->pivot->pieces_contributed;
                    $subOrder->assignedUsers()->updateExistingPivot($userId, [
                        'estacion'           => $estacionActual,
                        'pieces_contributed' => $currentContributed + $cantidad,
                    ]);
                } else {
                    $subOrder->assignedUsers()->attach($userId, [
                        'estacion'           => $estacionActual,
                        'pieces_contributed' => $cantidad,
                    ]);
                }

                // La suborden es la fase de ensamblaje: cada pieza registrada aquí
                // es una unidad de producto terminado -> se suma al stock.
                if ($subOrder->es_ensamblaje && $cantidad > 0) {
                    $orden->product()->increment('stock', $cantidad);
                }

                // Si el pedido principal seguía "pending", al recibir trabajo real
                // pasa a "in_progress" para que el admin lo vea reflejado de inmediato.
                if ($cantidad > 0 && strtolower($orden->status) === 'pending') {
                    $orden->status = 'in_progress';
                }
            }

            if ($cantidad > 0) {
                $orden->increment('completed_pieces', $cantidad);

                if ($orden->completed_pieces >= $orden->quantity) {
                    $orden->status = 'completed';
                }

                $orden->save();
            }
        });

        return redirect()->back()->with('success', '¡Registro guardado correctamente!');
    }

    // Endpoint ligero para que la vista de registro consulte, cada pocos segundos,
    // si quedan pocas piezas y quiénes están asignados — sin recargar toda la página.
    public function estadoSuborden(ProductionSubOrder $subOrder)
    {
        $subOrder->load('assignedUsers');

        return response()->json([
            'restantes'      => $subOrder->restantes,
            'alerta_cercana' => $subOrder->alerta_cercana,
            'completed'      => $subOrder->status === 'completed',
            'colegas'        => $subOrder->assignedUsers->map(fn ($u) => [
                'id'       => $u->id,
                'nombre'   => $u->name,
                'estacion' => $u->pivot->estacion,
                'aportadas'=> $u->pivot->pieces_contributed,
            ]),
        ]);
    }

    public function perfil()
    {
        $user = \App\Models\User::with(['skills', 'permissions'])->find(Auth::id());

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
            'estacion' => $user->planta ?? $ultimaOrden->estacion ?? 'Sin asignar',
            'turno' => $user->turno ?? 'Sin definir',
            'alta_desde' => optional($user->created_at)->translatedFormat('M Y') ?? '—',
        ];

        $habilidades = $user->skills->pluck('skill')->toArray();

        $permisos = $user->permissions->map(fn($perm) => [
            'nombre' => $perm->name,
            'descripcion' => 'Permiso clave: ' . $perm->slug,
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
            'permisos' => $permisos,
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
        $productionOrder->update(['status' => 'in_progress']);

        return redirect()->route('operario.tareas', ['orden' => $productionOrder->id])
            ->with('success', 'Tarea iniciada.');
    }

    public function completarTarea(ProductionOrder $productionOrder)
    {
        $productionOrder->update(['status' => 'completed']);

        return redirect()->route('operario.tareas', ['orden' => $productionOrder->id])
            ->with('success', 'Tarea marcada como completada.');
    }
}