@props(['status'])

@php
$estados = [
    'pendiente' => ['label' => 'Pendiente', 'bg' => 'bg-orange-50', 'text' => 'text-orange-700', 'border' => 'border-orange-200'],
    'resuelta' => ['label' => 'Resuelta', 'bg' => 'bg-emerald-50', 'text' => 'text-emerald-700', 'border' => 'border-emerald-200'],
];
$estado = $estados[$status] ?? ['label' => ucfirst($status), 'bg' => 'bg-slate-50', 'text' => 'text-slate-600', 'border' => 'border-slate-200'];
@endphp

<span {{ $attributes->merge(['class' => "text-xs font-bold {$estado['bg']} {$estado['text']} border {$estado['border']} px-3 py-1 rounded-full"]) }}>
    {{ $estado['label'] }}
</span>