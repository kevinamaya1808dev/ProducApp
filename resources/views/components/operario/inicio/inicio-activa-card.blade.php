@props(['ordenActiva', 'piezasOrdenActiva'])

@if($ordenActiva)
@php
    $estado = strtolower($ordenActiva->status);
    $piezasRestantes = max(0, $ordenActiva->quantity - $piezasOrdenActiva);
    
    if ($piezasRestantes <= 1) {
        $loteDinamico = 0;
    } elseif ($piezasRestantes <= 4) {
        $loteDinamico = $piezasRestantes; 
    } else {
        $loteDinamico = 5; 
    }
@endphp
<div class="bg-white dark:bg-stone-900 rounded-2xl shadow-sm border border-amber-100 dark:border-stone-800 overflow-hidden">
    <div class="bg-gradient-to-r from-orange-600 to-amber-600 dark:from-orange-700 dark:to-amber-700 p-4 flex justify-between items-center">
        <h2 class="text-white font-bold text-sm tracking-wide uppercase">
            {{ $estado === 'in_progress' ? 'Tarea Activa' : 'Nueva Tarea Asignada' }}
        </h2>
        
        <!-- Componente limpio de prioridad -->
        <x-operario.incidencia.urgencia-badge :orden="$ordenActiva" />
    </div>
    
    <div class="p-6">
        <div class="flex justify-between items-start mb-6">
            <div>
                <div class="flex items-center space-x-2 mb-1">
                    <span class="text-xs font-semibold text-stone-400 dark:text-stone-500 uppercase">Orden</span>
                    <span class="text-xs font-bold text-orange-700 dark:text-orange-400 bg-orange-50 dark:bg-orange-950/50 px-2 py-0.5 rounded">#{{ $ordenActiva->order_number }}</span>
                </div>
                <h3 class="text-2xl font-bold text-stone-800 dark:text-stone-100">{{ $ordenActiva->product->name ?? 'Producto sin nombre' }}</h3>
                <p class="text-stone-500 dark:text-stone-400 text-sm">{{ $ordenActiva->product->description ?? '' }}</p>
            </div>

            <div class="text-right">
                @if($ordenActiva->estacion)
                    <div class="text-sm text-stone-400 dark:text-stone-500 font-medium mb-1">Estación {{ $ordenActiva->estacion }}</div>
                @else
                    <div class="text-sm text-orange-500 dark:text-orange-400 font-medium mb-1">Sin estación asignada</div>
                @endif
            </div>
        </div>

        <div class="mb-8">
            <div class="flex justify-between items-end mb-2">
                <span class="text-xs font-bold text-stone-400 dark:text-stone-500 uppercase tracking-wide">Avance de Hoy</span>
                <div class="text-right">
                    <span class="text-3xl font-bold text-orange-600 dark:text-orange-500">{{ $piezasOrdenActiva }}</span>
                    <span class="text-stone-400 dark:text-stone-500 font-medium">/{{ $ordenActiva->quantity }} unidades</span>
                </div>
            </div>
            <div class="w-full bg-amber-100 dark:bg-stone-800 rounded-full h-3 mb-2">
                @php
                    $porcentajeOrden = $ordenActiva->quantity > 0
                        ? min(($piezasOrdenActiva / $ordenActiva->quantity) * 100, 100)
                        : 0;
                @endphp
                <div class="bg-gradient-to-r from-orange-500 to-amber-500 h-3 rounded-full transition-all duration-500" style="width: {{ $porcentajeOrden }}%"></div>
            </div>
            <div class="flex justify-between text-xs font-bold">
                <span class="text-stone-400 dark:text-stone-500">0%</span>
                <span class="text-orange-600 dark:text-orange-400">{{ round($porcentajeOrden) }}% completado</span>
                <span class="text-stone-400 dark:text-stone-500">100%</span>
            </div>
        </div>

        <div>
            @if($estado === 'pending')
                <div class="space-y-3">
                    <form action="{{ route('operario.tareas.iniciar', $ordenActiva->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <button type="submit" class="w-full bg-orange-600 hover:bg-orange-700 text-white font-bold py-3.5 rounded-xl shadow-sm transition-colors duration-200 text-center cursor-pointer">
                            Iniciar Tarea
                        </button>
                    </form>
                    <a href="{{ route('operario.incidencias') }}" class="w-full bg-red-600/10 hover:bg-red-600 text-red-700 dark:text-red-400 hover:text-white border border-red-200 dark:border-red-900/40 hover:border-red-600 font-bold py-3.5 rounded-xl shadow-sm transition-colors duration-200 text-sm flex items-center justify-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                        Reportar Incidencia
                    </a>
                </div>
            @elseif($estado === 'in_progress')
                <span class="text-xs font-bold text-stone-400 dark:text-stone-500 uppercase tracking-wide block mb-3">Registro Rápido y Acciones</span>
                
                @if($piezasRestantes > 0)
                    <form action="{{ route('operario.registro.guardar') }}" method="POST" class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-4">
                        @csrf
                        <input type="hidden" name="production_order_id" value="{{ $ordenActiva->id }}">
                        
                        @if($piezasRestantes >= 1)
                            <button type="submit" name="cantidad" value="1" class="bg-orange-600/15 hover:bg-orange-600 text-orange-700 dark:text-orange-300 hover:text-white border border-orange-200 dark:border-orange-800/60 hover:border-orange-600 font-bold py-4 rounded-xl shadow-sm transition-colors duration-200 text-lg cursor-pointer">
                                +1 Unidad
                            </button>
                        @else
                            <div></div>
                        @endif
                        
                        @if($loteDinamico >= 2)
                            <button type="submit" name="cantidad" value="{{ $loteDinamico }}" class="bg-amber-900/10 hover:bg-amber-900 text-amber-900 dark:text-amber-400 hover:text-white border border-amber-900/20 dark:border-amber-700/30 hover:border-amber-900 font-bold py-4 rounded-xl shadow-sm transition-colors duration-200 text-lg cursor-pointer">
                                +{{ $loteDinamico }} Lote
                            </button>
                        @else
                            <div></div>
                        @endif
                        
                        <a href="{{ route('operario.incidencias') }}" class="bg-red-600/10 hover:bg-red-600 text-red-700 dark:text-red-400 hover:text-white border border-red-200 dark:border-red-900/40 hover:border-red-600 font-bold py-4 rounded-xl shadow-sm transition-colors duration-200 text-lg flex items-center justify-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                            Reportar
                        </a>
                    </form>
                @else
                    <div class="bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-900/50 p-4 rounded-xl text-center text-emerald-700 dark:text-emerald-400 font-semibold text-sm mb-4">
                        ¡Orden de producción completada con éxito!
                    </div>
                @endif

                @if($piezasOrdenActiva >= $ordenActiva->quantity)
                    <form action="{{ route('operario.tareas.completar', $ordenActiva->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-3.5 px-4 rounded-xl shadow-sm transition-colors text-center cursor-pointer">
                            Marcar como Completada
                        </button>
                    </form>
                @endif
            @else
                <span class="block text-center text-sm text-stone-400 dark:text-stone-400 py-3 bg-stone-50 dark:bg-stone-800/60 rounded-xl border border-stone-100 dark:border-stone-700/60">
                    Esta tarea ya no admite acciones ({{ $ordenActiva->status }}).
                </span>
            @endif
        </div>
    </div>
</div>
@else
<div class="bg-white dark:bg-stone-900 rounded-2xl shadow-sm border border-amber-100 dark:border-stone-800 p-8 text-center text-stone-400 dark:text-stone-500">
    No tienes ninguna orden de producción activa asignada por ahora.
</div>
@endif