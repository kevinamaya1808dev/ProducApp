@extends('layouts.app')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-slate-800">Registro de Producción</h1>
    <p class="text-slate-500 text-sm">Registra las unidades producidas en tu tarea activa</p>
</div>

<div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
    <!-- Controles Izquierda -->
    <div class="lg:col-span-4 space-y-6">
        <x-registros-tarea-info :tarea="$tarea ?? null" />
        <x-registros-registro-rapido :routeGuardar="$routeGuardar ?? '#'" :ordenId="$ordenId ?? null" />
        <x-registros-entrada-manual :routeGuardar="$routeGuardar ?? '#'" :ordenId="$ordenId ?? null" />
    </div>

    <!-- Tabla Historial (Derecha) -->
    <div class="lg:col-span-8">
        <x-registros-historial-tabla :registros="$registros ?? []" />
    </div>
</div>
@endsection