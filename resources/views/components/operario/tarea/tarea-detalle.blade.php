@props(['orden'])

@php
$piezas = $orden->piezas_registradas;
$porcentaje = $orden->porcentaje_avance;
$restantes = max($orden->quantity - $piezas, 0);
@endphp

<div class="bg-white rounded-2xl shadow-sm border border-amber-100 overflow-hidden sticky top-24">
    <!-- Encabezado con gradiente cálido -->
    <div class="bg-gradient-to-r from-orange-600 to-amber-600 p-6 flex justify-between items-start">
        <div>
            <div class="flex space-x-2 mb-2 text-xs font-bold">
                <span class="bg-orange-500/80 backdrop-blur-sm text-white px-2.5 py-0.5 rounded-lg border border-white/10">{{ $orden->order_number }}</span>
                <span class="bg-white/20 backdrop-blur-sm text-white px-2.5 py-0.5 rounded-lg border border-white/10">#{{ $orden->id }}</span>
            </div>
            <h2 class="text-2xl font-bold text-white mb-1">{{ $orden->product->name ?? 'Producto sin nombre' }}</h2>
            <p class="text-orange-100 text-sm leading-relaxed">{{ $orden->product->description ?? '' }}</p>
        </div>
        <div class="flex flex-col items-end space-y-2">
            <x-operario.incidencia.urgencia-badge :orden="$orden" />
            <x-operario.incidencia.estado-badge :status="$orden->status" class="!bg-white/90" />
        </div>
    </div>

    <div class="p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
            <!-- Columna Progreso -->
            <div>
                <h4 class="text-xs font-bold text-stone-400 uppercase tracking-wide mb-3">Progreso</h4>
                <div class="flex items-end space-x-1 mb-2">
                    <span class="text-4xl font-bold text-stone-800">{{ $piezas }}</span>
                    <span class="text-stone-400 font-medium pb-1">/{{ $orden->quantity }} pzas</span>
                </div>
                <div class="w-full bg-stone-100 rounded-full h-2.5 mb-2 overflow-hidden">
                    <div class="bg-orange-600 h-2.5 rounded-full transition-all duration-500" style="width: {{ $porcentaje }}%"></div>
                </div>
                <div class="text-xs font-bold text-orange-600">{{ round($porcentaje) }}% completado</div>
            </div>

            <!-- Columna Detalles -->
            <div>
                <h4 class="text-xs font-bold text-stone-400 uppercase tracking-wide mb-3">Detalles</h4>
                <ul class="space-y-2 text-sm">
                    <li class="flex justify-between border-b border-stone-100 pb-1.5">
                        <span class="text-stone-500 font-medium">Estación</span>
                        <span class="font-semibold text-stone-800">{{ $orden->estacion ?? 'Sin asignar' }}</span>
                    </li>
                    <li class="flex justify-between border-b border-stone-100 pb-1.5">
                        <span class="text-stone-500 font-medium">Fecha Límite</span>
                        <span class="font-semibold text-stone-800">{{ $orden->end_date?->format('d M Y') ?? 'Sin fecha' }}</span>
                    </li>
                    <li class="flex justify-between border-b border-stone-100 pb-1.5">
                        <span class="text-stone-500 font-medium">Inicio</span>
                        <span class="font-semibold text-stone-800">{{ $orden->start_date?->format('d M Y') ?? 'Sin definir' }}</span>
                    </li>
                    <li class="flex justify-between pt-0.5">
                        <span class="text-stone-500 font-medium">Restante</span>
                        <span class="font-bold text-orange-700">{{ $restantes }} pzas</span>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Botones de Acción -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            @if($orden->status === 'pending')
                <form action="{{ route('operario.tareas.iniciar', $orden->id) }}" method="POST" class="col-span-1 sm:col-span-2">
                    @csrf
                    @method('PUT')
                    <button type="submit" class="w-full bg-orange-600 hover:bg-orange-700 text-white font-bold py-3 px-4 rounded-xl shadow-sm transition-colors text-center cursor-pointer">
                        Iniciar Tarea
                    </button>
                </form>
            @elseif($orden->status === 'in_progress')
                <a href="{{ route('operario.inicio') }}" class="bg-orange-600 hover:bg-orange-700 text-white font-bold py-3 px-4 rounded-xl shadow-sm transition-colors text-center flex items-center justify-center">
                    Registrar Unidades
                </a>
                <form action="{{ route('operario.tareas.completar', $orden->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-3 px-4 rounded-xl shadow-sm transition-colors text-center cursor-pointer">
                        Marcar como Completada
                    </button>
                </form>
            @else
                <span class="col-span-1 sm:col-span-2 text-center text-sm text-stone-400 py-3 bg-stone-50 rounded-xl border border-stone-100">
                    Esta tarea ya no admite acciones ({{ $orden->status }}).
                </span>
            @endif
        </div>
    </div>
</div>