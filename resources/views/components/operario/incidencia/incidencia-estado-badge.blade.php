@props(['status'])

@php
$estados = [
    'pendiente' => ['label' => 'Pendiente', 'bg' => 'bg-amber-50 dark:bg-amber-950/50', 'text' => 'text-amber-700 dark:text-amber-400', 'border' => 'border-amber-200 dark:border-amber-900/50'],
    'resuelta' => ['label' => 'Resuelta', 'bg' => 'bg-emerald-50 dark:bg-emerald-950/50', 'text' => 'text-emerald-700 dark:text-emerald-400', 'border' => 'border-emerald-200 dark:border-emerald-900/50'],
];
$estado = $estados[$status] ?? ['label' => ucfirst($status), 'bg' => 'bg-stone-50 dark:bg-stone-800', 'text' => 'text-stone-600 dark:text-stone-300', 'border' => 'border-stone-200 dark:border-stone-700'];
@endphp

<span {{ $attributes->merge(['class' => "text-xs font-bold {$estado['bg']} {$estado['text']} border {$estado['border']} px-3 py-1 rounded-full uppercase tracking-wider"]) }}>
    {{ $estado['label'] }}
</span>