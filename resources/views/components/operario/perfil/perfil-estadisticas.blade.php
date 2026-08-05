@props(['eficiencia' => '0%', 'ordenesCompletas' => 0, 'incidencias' => 0])

<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <div class="bg-white dark:bg-stone-900 rounded-2xl shadow-sm border border-amber-100 dark:border-stone-800 p-6">
        <h3 class="text-xs font-bold text-stone-400 dark:text-stone-500 uppercase tracking-wide mb-1">Eficiencia Prom.</h3>
        <div class="text-4xl font-bold text-orange-600 dark:text-orange-500 mb-1">{{ $eficiencia }}</div>
        <p class="text-xs text-stone-400 dark:text-stone-500">últimos 30 días</p>
    </div>
    <div class="bg-white dark:bg-stone-900 rounded-2xl shadow-sm border border-amber-100 dark:border-stone-800 p-6">
        <h3 class="text-xs font-bold text-stone-400 dark:text-stone-500 uppercase tracking-wide mb-1">Órdenes Completas</h3>
        <div class="text-4xl font-bold text-emerald-600 dark:text-emerald-400 mb-1">{{ $ordenesCompletas }}</div>
        <p class="text-xs text-stone-400 dark:text-stone-500">total histórico</p>
    </div>
    <div class="bg-white dark:bg-stone-900 rounded-2xl shadow-sm border border-amber-100 dark:border-stone-800 p-6">
        <h3 class="text-xs font-bold text-stone-400 dark:text-stone-500 uppercase tracking-wide mb-1">Incidencias</h3>
        <div class="text-4xl font-bold text-stone-800 dark:text-stone-100 mb-1">{{ $incidencias }}</div>
        <p class="text-xs text-stone-400 dark:text-stone-500">últimos 30 días</p>
    </div>
</div>