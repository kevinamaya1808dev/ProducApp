@extends('layouts.app')

@section('content')
<div class="mb-6 text-stone-500 text-sm font-medium">
    {{ auth()->user()->turno ?? 'Turno no asignado' }} · 
    Estación {{ $ordenActiva?->estacion ?? 'Sin asignar' }} · 
    {{ ucfirst(now()->translatedFormat('l d M Y')) }}
</div>

<div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
    <!-- Columna Izquierda -->
    <div class="lg:col-span-8 space-y-6">
        <x-operario.inicio.inicio-activa-card 
            :ordenActiva="$ordenActiva" 
            :piezasOrdenActiva="$piezasOrdenActiva" 
        />
    </div>

    <!-- Columna Derecha -->
    <div class="lg:col-span-4 space-y-6">
        <x-operario.inicio.inicio-turno-panel 
            :piezasHoy="$piezasHoy" 
            :eficiencia="$eficiencia" 
            :incidenciasHoy="$incidenciasHoy" 
        />
        <x-operario.inicio.inicio-actividad-reciente 
            :actividadesRecientes="$actividadesRecientes" 
        />
    </div>
</div>
@endsection