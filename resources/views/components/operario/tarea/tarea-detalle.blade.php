@props(['orden'])

@php
$piezas = $orden->piezas_registradas;
$porcentaje = $orden->porcentaje_avance;
$restantes = max($orden->quantity - $piezas, 0);
@endphp

<div class="bg-white dark:bg-stone-900 rounded-2xl shadow-sm border border-amber-100 dark:border-stone-800 overflow-hidden sticky top-24">
    <!-- Encabezado con gradiente cálido -->
    <div class="bg-gradient-to-r from-orange-600 to-amber-600 dark:from-orange-700 dark:to-amber-700 p-6 flex justify-between items-start">
        <div>
            <div class="flex space-x-2 mb-2 text-xs font-bold">
                <span class="bg-orange-500/80 backdrop-blur-sm text-white px-2.5 py-0.5 rounded-lg border border-white/10">{{ $orden->order_number }}</span>
                <span class="bg-white/20 backdrop-blur-sm text-white px-2.5 py-0.5 rounded-lg border border-white/10">#{{ $orden->id }}</span>
            </div>
            <h2 class="text-2xl font-bold text-white mb-1">{{ $orden->product->name ?? 'Producto sin nombre' }}</h2>
            <p class="text-orange-100 text-sm leading-relaxed">{{ $orden->product->description ?? '' }}</p>
        </div>
        <div class="flex flex-col items-end space-y-2">
            <x-operario.incidencia.urgencia-badge :orden="$orden" />
            <x-operario.incidencia.estado-badge :status="$orden->status" class="!bg-white/90 dark:!bg-stone-900/90 dark:!text-stone-100" />
        </div>
    </div>

    <div class="p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Columna Progreso -->
            <div>
                <h4 class="text-xs font-bold text-stone-400 dark:text-stone-500 uppercase tracking-wide mb-3">Progreso</h4>
                <div class="flex items-end space-x-1 mb-2">
                    <span class="text-4xl font-bold text-stone-800 dark:text-stone-100">{{ $piezas }}</span>
                    <span class="text-stone-400 dark:text-stone-500 font-medium pb-1">/{{ $orden->quantity }} pzas</span>
                </div>
                <div class="w-full bg-stone-100 dark:bg-stone-800 rounded-full h-2.5 mb-2 overflow-hidden">
                    <div class="bg-orange-600 h-2.5 rounded-full transition-all duration-500" style="width: {{ $porcentaje }}%"></div>
                </div>
                <div class="text-xs font-bold text-orange-600 dark:text-orange-400">{{ round($porcentaje) }}% completado</div>
            </div>

            <!-- Columna Detalles -->
            <div>
                <h4 class="text-xs font-bold text-stone-400 dark:text-stone-500 uppercase tracking-wide mb-3">Detalles</h4>
                <ul class="space-y-2 text-sm">
                    <li class="flex justify-between border-b border-stone-100 dark:border-stone-800 pb-1.5">
                        <span class="text-stone-500 dark:text-stone-400 font-medium">Estación</span>
                        <span class="font-semibold text-stone-800 dark:text-stone-200">{{ $orden->estacion ?? 'Sin asignar' }}</span>
                    </li>
                    <li class="flex justify-between border-b border-stone-100 dark:border-stone-800 pb-1.5">
                        <span class="text-stone-500 dark:text-stone-400 font-medium">Fecha Límite</span>
                        <span class="font-semibold text-stone-800 dark:text-stone-200">{{ $orden->end_date?->format('d M Y') ?? 'Sin fecha' }}</span>
                    </li>
                    <li class="flex justify-between border-b border-stone-100 dark:border-stone-800 pb-1.5">
                        <span class="text-stone-500 dark:text-stone-400 font-medium">Inicio</span>
                        <span class="font-semibold text-stone-800 dark:text-stone-200">{{ $orden->start_date?->format('d M Y') ?? 'Sin definir' }}</span>
                    </li>
                    <li class="flex justify-between pt-0.5">
                        <span class="text-stone-500 dark:text-stone-400 font-medium">Restante</span>
                        <span class="font-bold text-orange-700 dark:text-orange-400">{{ $restantes }} pzas</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>