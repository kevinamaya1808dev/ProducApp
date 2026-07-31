@props(['orden', 'activa' => false])

@php
$piezas = $orden->piezas_registradas;
$porcentaje = $orden->porcentaje_avance;
@endphp

<a href="{{ route('operario.tareas', ['orden' => $orden->id]) }}"
   class="block rounded-2xl border p-5 cursor-pointer transition-all duration-200 {{ $activa ? 'bg-white border-orange-500 ring-2 ring-orange-500/20' : 'bg-white border-amber-100 hover:border-orange-300 shadow-sm' }}">
    
    <div class="flex justify-between items-center mb-3">
        <x-operario.incidencia.estado-badge :status="$orden->status" />
        <x-operario.incidencia.urgencia-badge :orden="$orden" />
    </div>

    <div class="mb-3">
        <span class="text-xs font-bold text-orange-700 bg-orange-50 border border-orange-100/80 px-2 py-0.5 rounded-md inline-block mb-1">
            #{{ $orden->order_number }}
        </span>
        <h4 class="font-bold text-stone-800 text-base leading-tight">{{ $orden->product->name ?? 'Producto sin nombre' }}</h4>
        <p class="text-xs text-stone-500 line-clamp-1 mt-0.5">{{ $orden->product->description ?? '' }}</p>
    </div>

    <div>
        <div class="w-full bg-stone-100 rounded-full h-1.5 mb-2 overflow-hidden">
            <div class="bg-orange-500 h-1.5 rounded-full transition-all duration-300" style="width: {{ $porcentaje }}%"></div>
        </div>
        <div class="flex justify-between text-[11px] text-stone-500 font-semibold">
            <span>{{ $piezas }}/{{ $orden->quantity }} pzas</span>
            <span>Límite: {{ $orden->end_date?->format('d M Y') ?? 'Sin fecha' }}</span>
        </div>
    </div>
</a>