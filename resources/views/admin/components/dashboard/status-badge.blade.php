@php
    $map = [
        'completed'   => ['label' => 'Completado',   'classes' => 'bg-emerald-50 dark:bg-emerald-950/50 text-emerald-700 dark:text-emerald-300 border-emerald-100 dark:border-emerald-900/50', 'dot' => 'bg-emerald-600 dark:bg-emerald-400'],
        'in_progress' => ['label' => 'En Progreso',   'classes' => 'bg-orange-50 dark:bg-orange-950/50 text-orange-700 dark:text-orange-300 border-orange-100 dark:border-orange-900/50',   'dot' => 'bg-orange-600 dark:bg-orange-400'],
        'pending'     => ['label' => 'Pendiente',    'classes' => 'bg-amber-50 dark:bg-amber-950/50 text-amber-700 dark:text-amber-300 border-amber-100 dark:border-amber-900/50',     'dot' => 'bg-amber-600 dark:bg-amber-400'],
        'cancelled'   => ['label' => 'Cancelado',    'classes' => 'bg-rose-50 dark:bg-rose-950/50 text-rose-700 dark:text-rose-300 border-rose-100 dark:border-rose-900/50',         'dot' => 'bg-rose-600 dark:bg-rose-400'],
    ];
    $s = $map[$status] ?? ['label' => ucfirst($status ?? 'Desconocido'), 'classes' => 'bg-slate-50 dark:bg-stone-800 text-slate-700 dark:text-stone-300 border-slate-100 dark:border-stone-700', 'dot' => 'bg-slate-600 dark:bg-stone-400'];
@endphp

<span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-semibold border {{ $s['classes'] }}">
    <span class="w-1.5 h-1.5 rounded-full {{ $s['dot'] }}"></span>
    {{ $s['label'] }}
</span>