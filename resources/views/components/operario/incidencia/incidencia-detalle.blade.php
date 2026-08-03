@props(['incidencia'])

<div class="flex flex-col gap-6">
    <!-- Detalles Principales de la Incidencia -->
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
                <span class="text-stone-400 font-medium">Estado actual</span>
                <span class="font-semibold text-stone-700">{{ ucfirst($incidencia->status) }}</span>
            </li>
        </ul>
    </div>

    <!-- Historial y Seguimiento (Preparado para la interacción del Admin) -->
    <div class="bg-white rounded-2xl shadow-sm border border-amber-100 p-6">
        <h3 class="text-base font-bold text-stone-800 mb-5 flex items-center">
            <svg class="w-5 h-5 mr-2 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            Historial y Seguimiento
        </h3>

        <!-- Línea de tiempo -->
        <div class="relative border-l-2 border-stone-100 ml-2 space-y-6 pb-2">
            @forelse($incidencia->logs as $log)
                @php
                    // Configuración visual según el tipo de log
                    $iconData = match($log->type) {
                        'creacion' => ['bg' => 'bg-emerald-500', 'label' => 'Apertura', 'text' => 'text-emerald-700'],
                        'cambio_estado' => ['bg' => 'bg-blue-500', 'label' => 'Cambio de Estado', 'text' => 'text-blue-700'],
                        'cambio_prioridad' => ['bg' => 'bg-orange-500', 'label' => 'Cambio de Prioridad', 'text' => 'text-orange-700'],
                        'nota' => ['bg' => 'bg-amber-500', 'label' => 'Nota / Comentario', 'text' => 'text-amber-700'],
                        default => ['bg' => 'bg-stone-400', 'label' => 'Actividad', 'text' => 'text-stone-600'],
                    };
                @endphp
                
                <div class="relative pl-6">
                    <!-- Punto indicador -->
                    <span class="absolute -left-[5px] top-1.5 w-2.5 h-2.5 rounded-full {{ $iconData['bg'] }} ring-4 ring-white"></span>

                    <!-- Cabecera del Log (Usuario y Fecha) -->
                    <div class="mb-1 flex flex-wrap items-center justify-between gap-2">
                        <div class="flex items-center gap-2">
                            <span class="text-sm font-bold text-stone-800">{{ $log->user->name ?? 'Usuario' }}</span>
                            
                            <!-- Etiqueta de Rol (Para diferenciar Admin de Operario) -->
                            <span class="text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded border border-stone-200 bg-stone-50 text-stone-500">
                                {{ $log->user->role ?? 'Operario' }}
                            </span>
                        </div>
                        <span class="text-xs font-semibold text-stone-400">{{ $log->created_at->format('d M Y, H:i') }}</span>
                    </div>

                    <!-- Contenido del Log -->
                    <div class="bg-stone-50 border border-stone-100 rounded-xl p-3 mt-2">
                        <span class="font-semibold text-[11px] uppercase tracking-wide {{ $iconData['text'] }} mb-1 block">
                            {{ $iconData['label'] }}
                        </span>
                        <p class="text-sm text-stone-600 leading-relaxed">
                            {{ $log->comment }}
                        </p>
                    </div>
                </div>
            @empty
                <div class="pl-6 text-sm text-stone-500 font-medium italic">
                    No hay actividad registrada aún.
                </div>
            @endforelse
        </div>
    </div>
</div>