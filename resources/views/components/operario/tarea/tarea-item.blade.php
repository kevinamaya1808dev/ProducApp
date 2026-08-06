@props(['orden', 'activa' => false])

@php
$piezas = $orden->piezas_registradas;
$porcentaje = $orden->porcentaje_avance;
@endphp

<a href="{{ route('operario.tareas', ['orden' => $orden->id]) }}"
   class="block rounded-2xl border p-5 cursor-pointer transition-all duration-200 {{ $activa ? 'bg-white dark:bg-stone-900 border-orange-500 dark:border-orange-500 ring-2 ring-orange-500/20' : 'bg-white dark:bg-stone-900 border-amber-100 dark:border-stone-800 hover:border-orange-300 dark:hover:border-orange-500/50 shadow-sm' }}">
    
    <div class="flex justify-between items-center mb-3">
        <x-operario.incidencia.estado-badge :status="$orden->status" />
        
        <!-- Componente limpio de prioridad -->
        <x-operario.incidencia.urgencia-badge :orden="$orden" />
    </div>

    <div class="mb-3">
        <span class="text-xs font-bold text-orange-700 dark:text-orange-400 bg-orange-50 dark:bg-orange-950/40 border border-orange-100/80 dark:border-orange-900/50 px-2 py-0.5 rounded-md inline-block mb-1">
            #{{ $orden->order_number }}
        </span>
        <h4 class="font-bold text-stone-800 dark:text-stone-100 text-base leading-tight">{{ $orden->product->name ?? 'Producto sin nombre' }}</h4>
        <p class="text-xs text-stone-500 dark:text-stone-400 line-clamp-1 mt-0.5">{{ $orden->product->description ?? '' }}</p>
    </div>

    <div>
        <div class="w-full bg-stone-100 dark:bg-stone-800 rounded-full h-1.5 mb-2 overflow-hidden">
            <div class="bg-orange-500 h-1.5 rounded-full transition-all duration-300" style="width: {{ $porcentaje }}%"></div>
        </div>
        <div class="flex justify-between text-[11px] text-stone-500 dark:text-stone-400 font-semibold">
            <span>{{ $piezas }}/{{ $orden->quantity }} pzas</span>
            <span>Límite: {{ $orden->end_date?->format('d M Y') ?? 'Sin fecha' }}</span>
        </div>
    </div>
</a>