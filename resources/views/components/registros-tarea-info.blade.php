@props(['tarea' => null])

<div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
    <div class="bg-blue-600 p-4">
        <h3 class="text-xs font-bold text-blue-200 uppercase tracking-wide mb-1">Tarea Activa</h3>
        <h2 class="text-white font-bold text-lg">{{ $tarea['titulo'] ?? 'Costura de Mangas' }}</h2>
        <p class="text-blue-100 text-xs">{{ $tarea['descripcion'] ?? 'Chamarra de Mezclilla Mod. A' }}</p>
    </div>
    <div class="p-5">
        <div class="flex justify-between items-end mb-2">
            <span class="text-xs font-bold text-slate-400 uppercase tracking-wide">Avance Total</span>
            <div class="text-right">
                <span class="text-2xl font-bold text-slate-800">{{ $tarea['actual'] ?? 21 }}</span>
                <span class="text-slate-400 text-sm">/{{ $tarea['total'] ?? 100 }}</span>
            </div>
        </div>
        <div class="w-full bg-slate-100 rounded-full h-2 mb-1">
            @php
                $porcentaje = isset($tarea['actual'], $tarea['total']) && $tarea['total'] > 0 
                    ? min(($tarea['actual'] / $tarea['total']) * 100, 100) 
                    : 21;
            @endphp
            <div class="bg-orange-400 h-2 rounded-full transition-all duration-500" style="width: {{ $porcentaje }}%"></div>
        </div>
        <div class="text-right text-xs font-bold text-blue-600">{{ round($porcentaje) }}%</div>
    </div>
</div>