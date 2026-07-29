@props(['tarea' => null])

<div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
    @if($tarea)
        <div class="bg-blue-600 p-4">
            <h3 class="text-xs font-bold text-blue-200 uppercase tracking-wide mb-1">Tarea Activa</h3>
            <h2 class="text-white font-bold text-lg">{{ $tarea['titulo'] }}</h2>
            <p class="text-blue-100 text-xs">{{ $tarea['descripcion'] }}</p>
        </div>
        <div class="p-5">
            <div class="flex justify-between items-end mb-2">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wide">Avance Total</span>
                <div class="text-right">
                    <span class="text-2xl font-bold text-slate-800">{{ $tarea['actual'] }}</span>
                    <span class="text-slate-400 text-sm">/{{ $tarea['total'] }}</span>
                </div>
            </div>
            <div class="w-full bg-slate-100 rounded-full h-2 mb-1">
                @php
                    $porcentaje = $tarea['total'] > 0
                        ? min(($tarea['actual'] / $tarea['total']) * 100, 100)
                        : 0;
                @endphp
                <div class="bg-orange-400 h-2 rounded-full transition-all duration-500" style="width: {{ $porcentaje }}%"></div>
            </div>
            <div class="text-right text-xs font-bold text-blue-600">{{ round($porcentaje) }}%</div>
        </div>
    @else
        <div class="p-6 text-center text-slate-400 text-sm">
            No tienes ninguna tarea activa asignada por ahora.
        </div>
    @endif
</div>