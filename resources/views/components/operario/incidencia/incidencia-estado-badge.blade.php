@props(['status'])

@php
$estados = [
    'pendiente' => ['label' => 'Pendiente', 'bg' => 'bg-amber-50', 'text' => 'text-amber-700', 'border' => 'border-amber-200'],
    'resuelta' => ['label' => 'Resuelta', 'bg' => 'bg-emerald-50', 'text' => 'text-emerald-700', 'border' => 'border-emerald-200'],
];
$estado = $estados[$status] ?? ['label' => ucfirst($status), 'bg' => 'bg-stone-50', 'text' => 'text-stone-600', 'border' => 'border-stone-200'];
@endphp

<span {{ $attributes->merge(['class' => "text-xs font-bold {$estado['bg']} {$estado['text']} border {$estado['border']} px-3 py-1 rounded-full uppercase tracking-wider"]) }}>
    {{ $estado['label'] }}
</span>