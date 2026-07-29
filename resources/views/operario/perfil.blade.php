@extends('layouts.app')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-slate-800">Mi Perfil</h1>
    <p class="text-slate-500 text-sm">Tus estadísticas, historial y certificaciones</p>
</div>

<div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
    <!-- Columna Perfil (Izquierda) -->
    <div class="lg:col-span-3 space-y-6">
        <x-perfil-info-card />
        <x-perfil-habilidades />
        <x-perfil-certificaciones />
    </div>

    <!-- Columna Datos (Derecha) -->
    <div class="lg:col-span-9 space-y-6">
        <x-perfil-estadisticas />
        <x-perfil-produccion-grafica />
        <x-perfil-historial-ordenes />
    </div>
</div>
@endsection