@extends('layouts.app')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-slate-800">Mi Perfil</h1>
    <p class="text-slate-500 text-sm">Tus estadísticas, historial y certificaciones</p>
</div>

<div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
    <!-- Columna Perfil (Izquierda) -->
    <div class="lg:col-span-3 space-y-6">
        <x-perfil-info-card :usuario="$usuario ?? null" />
        <x-perfil-habilidades :habilidades="$habilidades ?? []" />
        <x-perfil-certificaciones :certificaciones="$certificaciones ?? []" />
    </div>

    <!-- Columna Datos (Derecha) -->
    <div class="lg:col-span-9 space-y-6">
        <x-perfil-estadisticas :eficiencia="$eficiencia ?? '0%'" :ordenesCompletas="$ordenesCompletas ?? 0" :incidencias="$incidencias ?? 0" />
        <x-perfil-produccion-grafica :rangoFechas="$rangoFechas ?? ''" :produccionSemana="$produccionSemana ?? []" :metaDiaria="$metaDiaria ?? 100" />
        <x-perfil-historial-ordenes :historial="$historial ?? []" />
    </div>
</div>
@endsection