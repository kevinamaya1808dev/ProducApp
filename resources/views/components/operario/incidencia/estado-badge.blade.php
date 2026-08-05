@props(['status'])

@php
$estados = [
    'pending' => ['label' => 'Pendiente', 'dot' => 'bg-amber-500', 'bg' => 'bg-amber-50 dark:bg-amber-950/50', 'text' => 'text-amber-700 dark:text-amber-400'],
    'in_progress' => ['label' => 'En Progreso', 'dot' => 'bg-orange-500', 'bg' => 'bg-orange-50 dark:bg-orange-950/50', 'text' => 'text-orange-700 dark:text-orange-400'],
    'completed' => ['label' => 'Completada', 'dot' => 'bg-emerald-500', 'bg' => 'bg-emerald-50 dark:bg-emerald-950/50', 'text' => 'text-emerald-700 dark:text-emerald-400'],
    'cancelled' => ['label' => 'Cancelada', 'dot' => 'bg-red-500', 'bg' => 'bg-red-50 dark:bg-red-950/50', 'text' => 'text-red-700 dark:text-red-400'],
];
$estado = $estados[$status] ?? ['label' => ucfirst($status), 'dot' => 'bg-stone-400', 'bg' => 'bg-stone-100 dark:bg-stone-800', 'text' => 'text-stone-600 dark:text-stone-300'];
@endphp

<span {{ $attributes->merge(['class' => "text-xs font-bold {$estado['bg']} {$estado['text']} px-2.5 py-1 rounded-full inline-flex items-center w-fit border border-amber-100/50 dark:border-stone-800"]) }}>
    <span class="w-1.5 h-1.5 {{ $estado['dot'] }} rounded-full mr-1.5"></span> {{ $estado['label'] }}
</span>