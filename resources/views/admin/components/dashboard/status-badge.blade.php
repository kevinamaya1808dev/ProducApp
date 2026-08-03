@php
    $map = [
        'completed'   => ['label' => 'Completado',  'classes' => 'bg-emerald-50 text-emerald-700 border-emerald-100', 'dot' => 'bg-emerald-600'],
        'in_progress' => ['label' => 'En Progreso',  'classes' => 'bg-orange-50 text-orange-700 border-orange-100',   'dot' => 'bg-orange-600'],
        'pending'     => ['label' => 'Pendiente',    'classes' => 'bg-amber-50 text-amber-700 border-amber-100',     'dot' => 'bg-amber-600'],
        'cancelled'   => ['label' => 'Cancelado',    'classes' => 'bg-rose-50 text-rose-700 border-rose-100',        'dot' => 'bg-rose-600'],
    ];
    $s = $map[$status] ?? ['label' => ucfirst($status ?? 'Desconocido'), 'classes' => 'bg-slate-50 text-slate-700 border-slate-100', 'dot' => 'bg-slate-600'];
@endphp

<span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-semibold border {{ $s['classes'] }}">
    <span class="w-1.5 h-1.5 rounded-full {{ $s['dot'] }}"></span>
    {{ $s['label'] }}
</span>