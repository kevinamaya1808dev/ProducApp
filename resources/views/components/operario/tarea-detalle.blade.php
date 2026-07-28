@props(['orden'])

@php
$piezas = $orden->piezas_registradas;
$porcentaje = $orden->porcentaje_avance;
$restantes = max($orden->quantity - $piezas, 0);
@endphp

<div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden sticky top-24">
    <div class="bg-blue-600 p-6 flex justify-between items-start">
        <div>
            <div class="flex space-x-2 mb-2 text-xs font-bold">
                <span class="bg-blue-500 text-white px-2 py-0.5 rounded">{{ $orden->order_number }}</span>
                <span class="bg-blue-50 text-blue-700 px-2 py-0.5 rounded">#{{ $orden->id }}</span>
            </div>
            <h2 class="text-2xl font-bold text-white mb-1">{{ $orden->product->name ?? 'Producto sin nombre' }}</h2>
            <p class="text-blue-100 text-sm">{{ $orden->product->description ?? '' }}</p>
        </div>
        <div class="flex flex-col items-end space-y-2">
            <x-operario.urgencia-badge :orden="$orden" />
            <x-operario.estado-badge :status="$orden->status" class="!bg-white/90" />
        </div>
    </div>

    <div class="p-6">
        <div class="grid grid-cols-2 gap-8 mb-8">
            <div>
                <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wide mb-3">Progreso</h4>
                <div class="flex items-end space-x-1 mb-2">
                    <span class="text-4xl font-bold text-slate-800">{{ $piezas }}</span>
                    <span class="text-slate-400 font-medium pb-1">/{{ $orden->quantity }} pzas</span>
                </div>
                <div class="w-full bg-slate-100 rounded-full h-2.5 mb-2">
                    <div class="bg-blue-600 h-2.5 rounded-full" style="width: {{ $porcentaje }}%"></div>
                </div>
                <div class="text-xs font-bold text-blue-600">{{ round($porcentaje) }}% completado</div>
            </div>

            <div>
                <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wide mb-3">Detalles</h4>
                <ul class="space-y-2 text-sm">
                    <li class="flex justify-between border-b border-slate-50 pb-1">
                        <span class="text-slate-500">Estación</span>
                        <span class="font-medium text-slate-800">{{ $orden->estacion ?? 'Sin asignar' }}</span>
                    </li>
                    <li class="flex justify-between border-b border-slate-50 pb-1">
                        <span class="text-slate-500">Fecha Límite</span>
                        <span class="font-medium text-slate-800">{{ $orden->end_date?->format('d M Y') ?? 'Sin fecha' }}</span>
                    </li>
                    <li class="flex justify-between border-b border-slate-50 pb-1">
                        <span class="text-slate-500">Inicio</span>
                        <span class="font-medium text-slate-800">{{ $orden->start_date?->format('d M Y') ?? 'Sin definir' }}</span>
                    </li>
                    <li class="flex justify-between">
                        <span class="text-slate-500">Restante</span>
                        <span class="font-medium text-slate-800">{{ $restantes }} pzas</span>
                    </li>
                </ul>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4">
            @if($orden->status === 'pending')
                <form action="{{ route('operario.tareas.iniciar', $orden->id) }}" method="POST" class="col-span-2">
                    @csrf
                    @method('PUT')
                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-xl shadow-sm transition-colors text-center">
                        Iniciar Tarea
                    </button>
                </form>
            @elseif($orden->status === 'in_progress')
                <a href="{{ route('operario.inicio') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-xl shadow-sm transition-colors text-center">
                    Registrar Unidades
                </a>
                <form action="{{ route('operario.tareas.completar', $orden->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-3 px-4 rounded-xl shadow-sm transition-colors text-center">
                        Marcar como Completada
                    </button>
                </form>
            @else
                <span class="col-span-2 text-center text-sm text-slate-400 py-3">Esta tarea ya no admite acciones ({{ $orden->status }}).</span>
            @endif
        </div>
    </div>
</div>