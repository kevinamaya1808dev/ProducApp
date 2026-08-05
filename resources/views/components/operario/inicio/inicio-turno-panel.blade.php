@props([
    'piezasHoy' => 0,
    'eficiencia' => 0,
    'incidenciasHoy' => 0
])

<div class="bg-white dark:bg-stone-900 rounded-2xl shadow-sm border border-amber-100 dark:border-stone-800 p-6">
    <h3 class="text-xs font-bold text-stone-400 dark:text-stone-500 uppercase tracking-wider mb-6">
        Mi Turno
    </h3>

    <div class="space-y-5">
        <!-- Piezas Producidas Hoy -->
        <div class="flex justify-between items-center">
            <span class="text-sm font-medium text-stone-600 dark:text-stone-400">Piezas hoy</span>
            <span class="text-lg font-bold text-stone-800 dark:text-stone-100">{{ $piezasHoy }}</span>
        </div>

        <!-- Eficiencia Calculada -->
        <div>
            <div class="flex justify-between items-center mb-2">
                <span class="text-sm font-medium text-stone-600 dark:text-stone-400">Eficiencia</span>
                <span class="text-sm font-bold text-stone-800 dark:text-stone-100">{{ $eficiencia }}%</span>
            </div>
            <div class="w-full bg-red-50 dark:bg-stone-800 rounded-full h-2">
                <div class="bg-red-500 h-2 rounded-full transition-all duration-500" 
                     style="width: {{ min($eficiencia, 100) }}%"></div>
            </div>
        </div>

        <!-- Incidencias del Día -->
        <div class="flex justify-between items-center pt-1">
            <span class="text-sm font-medium text-stone-600 dark:text-stone-400">Incidencias</span>
            <span class="text-lg font-bold {{ $incidenciasHoy > 0 ? 'text-amber-600 dark:text-amber-400' : 'text-stone-800 dark:text-stone-100' }}">
                {{ $incidenciasHoy }}
            </span>
        </div>
    </div>
</div>