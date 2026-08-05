@props(['tarea' => null])

<div class="bg-white dark:bg-stone-900 rounded-2xl shadow-sm border border-amber-100 dark:border-stone-800 overflow-hidden">
    @if($tarea)
        <div class="bg-gradient-to-r from-orange-600 to-amber-600 dark:from-orange-700 dark:to-amber-700 p-4">
            <h3 class="text-xs font-bold text-orange-100 uppercase tracking-wide mb-1">Tarea Activa</h3>
            <h2 class="text-white font-bold text-lg">{{ $tarea['titulo'] }}</h2>
            <p class="text-orange-50 text-xs">{{ $tarea['descripcion'] }}</p>
        </div>
        <div class="p-5">
            <div class="flex justify-between items-end mb-2">
                <span class="text-xs font-bold text-stone-400 dark:text-stone-500 uppercase tracking-wide">Avance Total</span>
                <div class="text-right">
                    <span class="text-2xl font-bold text-stone-800 dark:text-stone-100">{{ $tarea['actual'] }}</span>
                    <span class="text-stone-400 dark:text-stone-500 text-sm">/{{ $tarea['total'] }}</span>
                </div>
            </div>
            <div class="w-full bg-stone-100 dark:bg-stone-800 rounded-full h-2 mb-1 overflow-hidden">
                @php
                    $porcentaje = $tarea['total'] > 0
                        ? min(($tarea['actual'] / $tarea['total']) * 100, 100)
                        : 0;
                @endphp
                <div class="bg-orange-500 h-2 rounded-full transition-all duration-500" style="width: {{ $porcentaje }}%"></div>
            </div>
            <div class="text-right text-xs font-bold text-orange-600 dark:text-orange-400">{{ round($porcentaje) }}%</div>
        </div>
    @else
        <div class="p-6 text-center text-stone-400 dark:text-stone-500 text-sm">
            No tienes ninguna tarea activa asignada por ahora.
        </div>
    @endif
</div>