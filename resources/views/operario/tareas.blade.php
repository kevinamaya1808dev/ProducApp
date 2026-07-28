@extends('layouts.app')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-slate-800">Mis Tareas</h1>
    <p class="text-slate-500 text-sm">Tareas asignadas a tu turno · {{ $ordenes->count() }} órdenes</p>
</div>

<div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
    <!-- Lista de Tareas -->
    <div class="lg:col-span-4 space-y-4">
        @forelse($ordenes as $orden)
            <x-operario.tarea-item :orden="$orden" :activa="$ordenSeleccionada && $orden->id === $ordenSeleccionada->id" />
        @empty
            <div class="bg-white rounded-xl border border-slate-200 p-6 text-center text-slate-400 text-sm">
                No tienes tareas asignadas.
            </div>
        @endforelse
    </div>

    <!-- Detalle de Tarea -->
    <div class="lg:col-span-8">
        @if($ordenSeleccionada)
            <x-operario.tarea-detalle :orden="$ordenSeleccionada" />
        @else
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-10 text-center text-slate-400">
                Selecciona una tarea de la lista para ver su detalle.
            </div>
        @endif
    </div>
</div>
@endsection