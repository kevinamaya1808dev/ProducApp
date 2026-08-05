@extends('layouts.app')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-stone-800 dark:text-stone-100">Mis Tareas</h1>
    <p class="text-stone-500 dark:text-stone-400 text-sm">Tareas asignadas a tu turno · {{ $ordenes->count() }} órdenes</p>
</div>

<div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
    <!-- Lista de Tareas -->
    <div class="lg:col-span-4 space-y-4">
        @forelse($ordenes as $orden)
            <x-operario.tarea.tarea-item :orden="$orden" :activa="$ordenSeleccionada && $orden->id === $ordenSeleccionada->id" />
        @empty
            <div class="bg-white dark:bg-stone-900 rounded-2xl border border-amber-100 dark:border-stone-800 p-8 text-center text-stone-400 dark:text-stone-500 text-sm shadow-sm">
                No tienes tareas asignadas.
            </div>
        @endforelse
    </div>

    <!-- Detalle de Tarea -->
    <div class="lg:col-span-8">
        @if($ordenSeleccionada)
            <x-operario.tarea.tarea-detalle :orden="$ordenSeleccionada" />
        @else
            <div class="bg-amber-50/50 dark:bg-stone-900/40 border-2 border-dashed border-amber-200/80 dark:border-stone-800 rounded-2xl flex flex-col items-center justify-center h-64 text-center p-6">
                <div class="w-14 h-14 bg-white dark:bg-stone-800 rounded-2xl shadow-sm border border-amber-100 dark:border-stone-700 flex items-center justify-center mb-3">
                    <span class="text-2xl">📦</span>
                </div>
                <h3 class="text-base font-bold text-stone-700 dark:text-stone-200 mb-0.5">Selecciona una tarea</h3>
                <p class="text-xs text-stone-400 dark:text-stone-500">Elige una de la lista para consultar los detalles completos</p>
            </div>
        @endif
    </div>
</div>
@endsection