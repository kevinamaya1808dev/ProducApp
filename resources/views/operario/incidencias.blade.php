@extends('layouts.app')

@section('content')
<div class="flex justify-between items-center mb-6">
    <div>
        <h1 class="text-2xl font-bold text-stone-800 dark:text-stone-100">Incidencias</h1>
        <p class="text-stone-500 dark:text-stone-400 text-sm">Reporta y da seguimiento a incidencias de tu turno</p>
    </div>
    <a href="{{ route('operario.incidencias', ['nueva' => 1]) }}" class="bg-orange-600 hover:bg-orange-700 text-white font-bold py-2.5 px-5 rounded-xl shadow-sm transition-colors duration-200 flex items-center text-sm cursor-pointer">
        <span class="mr-2 text-lg leading-none">+</span> Reportar Incidencia
    </a>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <!-- Lista de Incidencias -->
    <div class="space-y-4">
        @forelse($incidencias as $incidencia)
            <x-operario.incidencia.incidencia-item :incidencia="$incidencia" :activa="$incidenciaSeleccionada && $incidencia->id === $incidenciaSeleccionada->id" />
        @empty
            <div class="bg-white dark:bg-stone-900 rounded-2xl border border-amber-100 dark:border-stone-800 p-8 text-center text-stone-400 dark:text-stone-500 text-sm shadow-sm">
                No has reportado incidencias.
            </div>
        @endforelse
    </div>

    <!-- Detalle / Formulario -->
    <div>
        @if($mostrarFormulario)
            <x-operario.incidencia.incidencia-form :ordenes="$ordenes" />
        @elseif($incidenciaSeleccionada)
            <x-operario.incidencia.incidencia-detalle :incidencia="$incidenciaSeleccionada" />
        @else
            <div class="bg-amber-50/50 dark:bg-stone-900/50 border-2 border-dashed border-amber-200/80 dark:border-stone-800 rounded-2xl flex flex-col items-center justify-center h-64 text-center p-6">
                <div class="w-14 h-14 bg-white dark:bg-stone-800 rounded-2xl shadow-sm border border-amber-100 dark:border-stone-700 flex items-center justify-center mb-3">
                    <span class="text-2xl">📋</span>
                </div>
                <h3 class="text-base font-bold text-stone-700 dark:text-stone-200 mb-0.5">Selecciona una incidencia</h3>
                <p class="text-xs text-stone-400 dark:text-stone-500">o reporta una nueva para comenzar</p>
            </div>
        @endif
    </div>
</div>
@endsection