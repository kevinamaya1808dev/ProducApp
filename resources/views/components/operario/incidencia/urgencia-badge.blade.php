@props(['orden'])

@php
$dias = $orden->end_date ? now()->diffInDays($orden->end_date, false) : null;

if (is_null($dias)) {
    $urgencia = ['label' => 'Sin fecha', 'color' => 'text-stone-400'];
} elseif ($dias < 0) {
    $urgencia = ['label' => 'Vencida', 'color' => 'text-red-600'];
} elseif ($dias <= 2) {
    $urgencia = ['label' => 'Alta', 'color' => 'text-red-500'];
} elseif ($dias <= 5) {
    $urgencia = ['label' => 'Media', 'color' => 'text-amber-600'];
} else {
    $urgencia = ['label' => 'Baja', 'color' => 'text-stone-400'];
}
@endphp

<span class="text-xs font-bold {{ $urgencia['color'] }}">{{ $urgencia['label'] }}</span>