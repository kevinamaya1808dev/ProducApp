@extends('layouts.app')

@section('content')
<div class="flex justify-between items-center mb-6">
    <div>
        <h1 class="text-2xl font-bold text-slate-800">Incidencias</h1>
        <p class="text-slate-500 text-sm">Reporta y da seguimiento a incidencias de tu turno</p>
    </div>
    <a href="{{ route('operario.incidencias', ['nueva' => 1]) }}" class="bg-orange-500 hover:bg-orange-600 text-white font-bold py-2.5 px-5 rounded-xl shadow-sm transition-colors flex items-center">
        <span class="mr-2">+</span> Reportar Incidencia
    </a>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <!-- Lista de Incidencias -->
    <div class="space-y-4">
        @forelse($incidencias as $incidencia)
            <x-operario.incidencia.incidencia-item :incidencia="$incidencia" :activa="$incidenciaSeleccionada && $incidencia->id === $incidenciaSeleccionada->id" />
        @empty
            <div class="bg-white rounded-xl border border-slate-200 p-6 text-center text-slate-400 text-sm">
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
            <div class="bg-slate-50 border-2 border-dashed border-slate-200 rounded-2xl flex flex-col items-center justify-center h-64 text-center p-6">
                <div class="w-16 h-16 bg-white rounded-xl shadow-sm flex items-center justify-center mb-4">
                    <span class="text-3xl">📋</span>
                </div>
                <h3 class="text-lg font-bold text-slate-700 mb-1">Selecciona una incidencia</h3>
                <p class="text-sm text-slate-400">o reporta una nueva</p>
            </div>
        @endif
    </div>
</div>
@endsection