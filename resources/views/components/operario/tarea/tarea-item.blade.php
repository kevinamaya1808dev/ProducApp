@props(['orden', 'activa' => false])

@php
$piezas = $orden->piezas_registradas;
$porcentaje = $orden->porcentaje_avance;
@endphp

<a href="{{ route('operario.tareas', ['orden' => $orden->id]) }}"
   class="block rounded-xl border-2 p-4 cursor-pointer transition-colors {{ $activa ? 'bg-blue-50 border-blue-400' : 'bg-white border-slate-200 hover:border-slate-300 shadow-sm' }}">
    <div class="flex justify-between items-center mb-2">
        <x-operario.incidencia.estado-badge :status="$orden->status" />
        <x-operario.incidencia.urgencia-badge :orden="$orden" />
    </div>
    <div class="mb-3">
        <span class="text-xs font-bold text-blue-600">#{{ $orden->order_number }}</span>
        <h4 class="font-bold text-slate-800">{{ $orden->product->name ?? 'Producto sin nombre' }}</h4>
        <p class="text-xs text-slate-500">{{ $orden->product->description ?? '' }}</p>
    </div>
    <div>
        <div class="w-full bg-slate-100 rounded-full h-1.5 mb-1">
            <div class="bg-blue-600 h-1.5 rounded-full" style="width: {{ $porcentaje }}%"></div>
        </div>
        <div class="flex justify-between text-[10px] text-slate-500 font-medium">
            <span>{{ $piezas }}/{{ $orden->quantity }} pzas</span>
            <span>Límite: {{ $orden->end_date?->format('d M Y') ?? 'Sin fecha' }}</span>
        </div>
    </div>
</a>