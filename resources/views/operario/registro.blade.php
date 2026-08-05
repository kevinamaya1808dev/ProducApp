@extends('layouts.app')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-stone-800 dark:text-stone-100">Registro de Producción</h1>
    <p class="text-stone-500 dark:text-stone-400 text-sm">Registra las unidades producidas en tu tarea activa</p>
</div>

<div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
    <!-- Controles Izquierda -->
    <div class="lg:col-span-4 space-y-6">
        <x-operario.registro.registros-tarea-info :tarea="$tarea ?? null" />
        <x-operario.registro.registros-registro-rapido :routeGuardar="$routeGuardar ?? '#'" :ordenId="$ordenId ?? null" />
        <x-operario.registro.registros-entrada-manual :routeGuardar="$routeGuardar ?? '#'" :ordenId="$ordenId ?? null" />
    </div>

    <!-- Tabla Historial (Derecha) -->
    <div class="lg:col-span-8">
        <x-operario.registro.registros-historial-tabla :registros="$registros ?? []" />
    </div>
</div>
@endsection