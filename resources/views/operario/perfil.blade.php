@extends('layouts.app')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-stone-800 dark:text-stone-100">Mi Perfil</h1>
    <p class="text-stone-500 dark:text-stone-400 text-sm">Tus estadísticas, historial y certificaciones</p>
</div>

<div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
    <!-- Columna Perfil (Izquierda) -->
    <div class="lg:col-span-3 space-y-6">
        <x-operario.perfil.perfil-info-card :usuario="$usuario ?? null" />
    
        <x-operario.perfil.perfil-permisos :permisos="$permisos ?? []" />
    </div>

    <!-- Columna Datos (Derecha) -->
    <div class="lg:col-span-9 space-y-6">
        <x-operario.perfil.perfil-estadisticas :eficiencia="$eficiencia ?? '0%'" :ordenesCompletas="$ordenesCompletas ?? 0" :incidencias="$incidencias ?? 0" />
        <x-operario.perfil.perfil-produccion-grafica :rangoFechas="$rangoFechas ?? ''" :produccionSemana="$produccionSemana ?? []" :metaDiaria="$metaDiaria ?? 100" />
        <x-operario.perfil.perfil-historial-ordenes :historial="$historial ?? []" />
    </div>
</div>
@endsection