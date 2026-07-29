@props(['eficiencia' => '0%', 'ordenesCompletas' => 0, 'incidencias' => 0])

<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
        <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wide mb-1">Eficiencia Prom.</h3>
        <div class="text-4xl font-bold text-blue-600 mb-1">{{ $eficiencia }}</div>
        <p class="text-xs text-slate-400">últimos 30 días</p>
    </div>
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
        <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wide mb-1">Órdenes Completas</h3>
        <div class="text-4xl font-bold text-emerald-600 mb-1">{{ $ordenesCompletas }}</div>
        <p class="text-xs text-slate-400">total histórico</p>
    </div>
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
        <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wide mb-1">Incidencias</h3>
        <div class="text-4xl font-bold text-slate-800 mb-1">{{ $incidencias }}</div>
        <p class="text-xs text-slate-400">últimos 30 días</p>
    </div>
</div>