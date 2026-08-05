@props(['incidencia', 'activa' => false])

@php
    $coloresImportancia = [
        'alta' => 'text-red-700 dark:text-red-400 bg-red-50 dark:bg-red-950/50 border-red-200 dark:border-red-900/50',
        'media' => 'text-amber-700 dark:text-amber-400 bg-amber-50 dark:bg-amber-950/50 border-amber-200 dark:border-amber-900/50',
        'baja' => 'text-stone-600 dark:text-stone-400 bg-stone-50 dark:bg-stone-800 border-stone-200 dark:border-stone-700',
    ];
    $color = $coloresImportancia[$incidencia->importance ?? 'baja'];
@endphp

<a href="{{ route('operario.incidencias', ['incidencia' => $incidencia->id]) }}"
   class="block bg-white dark:bg-stone-900 rounded-2xl shadow-sm border p-5 cursor-pointer transition-all duration-200 {{ $activa ? 'border-orange-500 ring-2 ring-orange-500/20' : 'border-amber-100 dark:border-stone-800 hover:border-orange-300 dark:hover:border-orange-700' }}">
    <div class="flex justify-between items-center mb-3">
        <span class="text-xs font-bold text-stone-400 dark:text-stone-500">INC-{{ str_pad($incidencia->id, 4, '0', STR_PAD_LEFT) }}</span>
        <div class="flex items-center gap-2">
            <span class="text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded-md border {{ $color }}">
                {{ $incidencia->importance ?? 'baja' }}
            </span>
            <x-operario.incidencia.incidencia-estado-badge :status="$incidencia->status" />
        </div>
    </div>
    <h3 class="font-bold text-stone-800 dark:text-stone-100 mb-1 text-base">{{ $incidencia->title }}</h3>
    <p class="text-sm text-stone-500 dark:text-stone-400 mb-3 line-clamp-2 leading-relaxed">{{ $incidencia->description }}</p>
    <div class="flex items-center text-xs text-stone-400 dark:text-stone-500 font-semibold space-x-4">
        <span>{{ $incidencia->created_at->format('d M Y · H:i') }}</span>
        <span class="text-orange-700 dark:text-orange-400 bg-orange-50 dark:bg-orange-950/50 px-1.5 py-0.5 rounded">#{{ $incidencia->order->order_number ?? 'Sin orden' }}</span>
    </div>
</a>