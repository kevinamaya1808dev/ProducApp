@props(['status'])

@php
$estados = [
    'pending' => ['label' => 'Pendiente', 'dot' => 'bg-amber-500', 'bg' => 'bg-amber-100', 'text' => 'text-amber-700'],
    'in_progress' => ['label' => 'En Progreso', 'dot' => 'bg-blue-600', 'bg' => 'bg-blue-100', 'text' => 'text-blue-700'],
    'completed' => ['label' => 'Completada', 'dot' => 'bg-emerald-500', 'bg' => 'bg-emerald-100', 'text' => 'text-emerald-700'],
    'cancelled' => ['label' => 'Cancelada', 'dot' => 'bg-red-500', 'bg' => 'bg-red-100', 'text' => 'text-red-700'],
];
$estado = $estados[$status] ?? ['label' => ucfirst($status), 'dot' => 'bg-slate-400', 'bg' => 'bg-slate-100', 'text' => 'text-slate-600'];
@endphp

<span {{ $attributes->merge(['class' => "text-xs font-bold {$estado['bg']} {$estado['text']} px-2 py-0.5 rounded-full inline-flex items-center w-fit"]) }}>
    <span class="w-1.5 h-1.5 {{ $estado['dot'] }} rounded-full mr-1.5"></span> {{ $estado['label'] }}
</span>