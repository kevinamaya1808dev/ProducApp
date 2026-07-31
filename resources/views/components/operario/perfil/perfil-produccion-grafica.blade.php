@props(['rangoFechas' => '', 'produccionSemana' => [], 'metaDiaria' => 100])

<div class="bg-white rounded-2xl shadow-sm border border-amber-100 p-6">
    <!-- Encabezado de la Gráfica -->
    <div class="flex justify-between items-start mb-6">
        <div>
            <h3 class="text-lg font-bold text-stone-800">Producción Semanal</h3>
            <p class="text-sm text-stone-500">{{ $rangoFechas }}</p>
        </div>
        <div class="bg-amber-50 px-3 py-1.5 rounded-xl border border-amber-200/60 text-xs font-bold text-amber-800 flex items-center gap-1.5">
            <span class="w-2 h-2 rounded-full bg-amber-500"></span>
            Meta: {{ $metaDiaria }} pzas/día
        </div>
    </div>

    @if(count($produccionSemana) > 0)
        <!-- Contenedor Principal de la Gráfica -->
        <div class="relative h-52 pt-8 pb-2 border-b border-stone-100">
            
            <!-- Línea Guía de Meta (100%) -->
            <div class="absolute top-8 left-0 right-0 border-t-2 border-dashed border-amber-300/80 z-10 flex justify-end">
                <span class="text-[10px] font-bold text-amber-700 bg-amber-50 px-1.5 py-0.5 rounded -mt-2.5 mr-1 border border-amber-200/80 shadow-xs">
                    Meta 100%
                </span>
            </div>

            <!-- Columnas de Producción -->
            <div class="flex items-end justify-between h-full px-2 md:px-6 gap-2 md:gap-4 relative z-20">
                @foreach($produccionSemana as $dia)
                    @php
                        // Calcula el porcentaje real sobre la meta
                        $porcentajeCalculado = $metaDiaria > 0 ? ($dia['piezas'] / $metaDiaria) * 100 : 0;
                        
                        // Limita la altura visual entre 2% (mínimo visible) y 100%
                        $alturaVisual = min(max($porcentajeCalculado, 2), 100);

                        // Determina el color dinámico según el cumplimiento
                        $color = $dia['cumplida'] 
                            ? 'bg-emerald-500' 
                            : ($porcentajeCalculado >= 60 ? 'bg-orange-500' : 'bg-amber-400');
                    @endphp

                    <div class="flex flex-col items-center flex-1 h-full justify-end group">
                        <!-- Cantidad de Piezas -->
                        <span class="text-xs font-bold {{ $dia['cumplida'] ? 'text-emerald-700' : 'text-stone-600' }} mb-2 transition-transform duration-200 group-hover:-translate-y-1">
                            {{ $dia['piezas'] }}
                        </span>

                        <!-- Pista Gris de Fondo + Barra Color -->
                        <div class="w-full max-w-[38px] bg-stone-100 rounded-t-xl h-full flex items-end overflow-hidden">
                            <div class="w-full {{ $color }} rounded-t-xl transition-all duration-500 ease-out group-hover:brightness-105"
                                 style="height: {{ $alturaVisual }}%;">
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Etiquetas de Días (L, M, X, J, V) -->
        <div class="flex justify-between px-2 md:px-6 mt-3 text-xs font-bold uppercase">
            @foreach($produccionSemana as $dia)
                <div class="flex-1 text-center {{ $dia['cumplida'] ? 'text-emerald-600 font-extrabold' : 'text-stone-400' }}">
                    {{ $dia['label'] }}
                </div>
            @endforeach
        </div>
    @else
        <div class="flex flex-col items-center justify-center py-12 text-stone-400 text-sm">
            <span class="text-3xl mb-2">📊</span>
            <p>Sin datos de producción para esta semana.</p>
        </div>
    @endif
</div>