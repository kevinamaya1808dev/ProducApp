@props(['rangoFechas' => '', 'produccionSemana' => [], 'metaDiaria' => 100])

<div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
    <div class="flex justify-between items-start mb-6">
        <div>
            <h3 class="text-lg font-bold text-slate-800">Producción Semanal</h3>
            <p class="text-sm text-slate-500">{{ $rangoFechas }}</p>
        </div>
        <div class="bg-slate-50 px-3 py-1.5 rounded-lg border border-slate-100 text-xs font-medium text-slate-500">
            Meta: {{ $metaDiaria }} pzas/día
        </div>
    </div>

    @if(count($produccionSemana) > 0)
        <div class="flex items-end justify-between h-48 pt-4 pb-2 border-b border-slate-100 px-4 md:px-10">
            @foreach($produccionSemana as $dia)
                @php
                    $color = $dia['cumplida'] ? 'bg-emerald-500' : ($dia['porcentaje'] >= 60 ? 'bg-blue-500' : 'bg-amber-400');
                @endphp
                <div class="flex flex-col items-center w-1/6">
                    <span class="text-xs font-bold text-slate-600 mb-2">{{ $dia['piezas'] }}</span>
                    <div class="w-full {{ $color }} rounded-t-md relative" style="height: {{ max($dia['porcentaje'], 2) }}%;">
                        @if($dia['cumplida'])
                            <div class="absolute -top-6 left-0 right-0 border-t-2 border-dashed border-slate-300"></div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
        <div class="flex justify-between px-4 md:px-10 mt-3 text-xs font-bold text-slate-400 uppercase">
            @foreach($produccionSemana as $dia)
                <div class="w-1/6 text-center {{ $dia['cumplida'] ? 'text-emerald-600' : '' }}">{{ $dia['label'] }}</div>
            @endforeach
        </div>
    @else
        <p class="text-sm text-slate-400 text-center py-10">Sin datos de producción para esta semana.</p>
    @endif
</div>