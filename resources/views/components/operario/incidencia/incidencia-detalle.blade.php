@props(['incidencia'])

<div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
    <div class="flex justify-between items-start mb-4">
        <div>
            <span class="text-xs font-bold text-slate-400">INC-{{ str_pad($incidencia->id, 4, '0', STR_PAD_LEFT) }}</span>
            <h2 class="text-xl font-bold text-slate-800">{{ $incidencia->title }}</h2>
        </div>
        <x-operario.incidencia.incidencia-estado-badge :status="$incidencia->status" />
    </div>

    <p class="text-sm text-slate-600 mb-6">{{ $incidencia->description }}</p>

    <ul class="space-y-2 text-sm">
        <li class="flex justify-between border-b border-slate-50 pb-1">
            <span class="text-slate-500">Orden relacionada</span>
            <span class="font-medium text-slate-800">{{ $incidencia->order->order_number ?? 'Sin orden' }}</span>
        </li>
        <li class="flex justify-between border-b border-slate-50 pb-1">
            <span class="text-slate-500">Fecha de reporte</span>
            <span class="font-medium text-slate-800">{{ $incidencia->created_at->format('d M Y · H:i') }}</span>
        </li>
        <li class="flex justify-between">
            <span class="text-slate-500">Estado</span>
            <span class="font-medium text-slate-800">{{ ucfirst($incidencia->status) }}</span>
        </li>
    </ul>
</div>