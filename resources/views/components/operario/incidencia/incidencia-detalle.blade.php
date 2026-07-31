@props(['incidencia'])

<div class="bg-white rounded-2xl shadow-sm border border-amber-100 p-6">
    <div class="flex justify-between items-start mb-4">
        <div>
            <span class="text-xs font-bold text-stone-400 uppercase tracking-wide">INC-{{ str_pad($incidencia->id, 4, '0', STR_PAD_LEFT) }}</span>
            <h2 class="text-xl font-bold text-stone-800 mt-0.5">{{ $incidencia->title }}</h2>
        </div>
        <x-operario.incidencia.incidencia-estado-badge :status="$incidencia->status" />
    </div>

    <p class="text-sm text-stone-600 mb-6 leading-relaxed">{{ $incidencia->description }}</p>

    <ul class="space-y-3 text-sm">
        <li class="flex justify-between border-b border-stone-100 pb-2">
            <span class="text-stone-400 font-medium">Orden relacionada</span>
            <span class="font-bold text-orange-700 bg-orange-50 px-2 py-0.5 rounded text-xs">#{{ $incidencia->order->order_number ?? 'Sin orden' }}</span>
        </li>
        <li class="flex justify-between border-b border-stone-100 pb-2">
            <span class="text-stone-400 font-medium">Fecha de reporte</span>
            <span class="font-semibold text-stone-700">{{ $incidencia->created_at->format('d M Y · H:i') }}</span>
        </li>
        <li class="flex justify-between border-b border-stone-100 pb-2">
            <span class="text-stone-400 font-medium">Importancia</span>
            <span class="font-semibold text-stone-700">{{ ucfirst($incidencia->importance ?? 'Baja') }}</span>
        </li>
        <li class="flex justify-between">
            <span class="text-stone-400 font-medium">Estado</span>
            <span class="font-semibold text-stone-700">{{ ucfirst($incidencia->status) }}</span>
        </li>
    </ul>
</div>