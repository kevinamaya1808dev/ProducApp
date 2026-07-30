@props(['incidencia', 'activa' => false])

@php
    $coloresImportancia = [
        'alta' => 'text-red-700 bg-red-50 border-red-200',
        'media' => 'text-amber-700 bg-amber-50 border-amber-200',
        'baja' => 'text-slate-600 bg-slate-50 border-slate-200',
    ];
    $color = $coloresImportancia[$incidencia->importance ?? 'baja'];
@endphp

<a href="{{ route('operario.incidencias', ['incidencia' => $incidencia->id]) }}"
   class="block bg-white rounded-xl shadow-sm border p-5 cursor-pointer transition-colors {{ $activa ? 'border-orange-300' : 'border-slate-200 hover:border-orange-300' }}">
    <div class="flex justify-between items-center mb-3">
        <span class="text-xs font-bold text-slate-400">INC-{{ str_pad($incidencia->id, 4, '0', STR_PAD_LEFT) }}</span>
        <div class="flex items-center gap-2">
            <span class="text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded border {{ $color }}">
                {{ $incidencia->importance ?? 'baja' }}
            </span>
            <x-operario.incidencia.incidencia-estado-badge :status="$incidencia->status" />
        </div>
    </div>
    <h3 class="font-bold text-slate-800 mb-1">{{ $incidencia->title }}</h3>
    <p class="text-sm text-slate-500 mb-3 line-clamp-2">{{ $incidencia->description }}</p>
    <div class="flex items-center text-xs text-slate-400 font-medium space-x-4">
        <span>{{ $incidencia->created_at->format('d M Y · H:i') }}</span>
        <span>{{ $incidencia->order->order_number ?? 'Sin orden' }}</span>
    </div>
</a>