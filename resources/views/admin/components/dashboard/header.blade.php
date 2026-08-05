<div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
    <div>
        <h1 class="text-2xl font-bold text-slate-900 dark:text-stone-100">Resumen de Producción</h1>
        <p class="text-xs text-slate-500 dark:text-stone-400 mt-1">
            Turno Matutino &middot; Planta Principal &middot; Actualizado en tiempo real
        </p>
    </div>

    <div class="flex items-center gap-3">
        <!-- Filtrar -->
        <button type="button" onclick="openModal('filter-modal')" class="inline-flex items-center gap-2 px-3.5 py-2 bg-white dark:bg-stone-900 border border-slate-200 dark:border-stone-800 rounded-lg text-xs font-semibold text-slate-700 dark:text-stone-300 hover:bg-slate-50 dark:hover:bg-stone-800 shadow-sm transition-colors">
            <svg class="w-4 h-4 text-slate-500 dark:text-stone-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
            Filtrar
            @if(request('status') || request('date'))
                <span class="w-2 h-2 rounded-full bg-orange-600"></span>
            @endif
        </button>

        <!-- Exportar: acción protegida -->
        @can('view-admin-dashboard')
        <button type="button" onclick="openModal('export-modal')" class="inline-flex items-center gap-2 px-3.5 py-2 bg-orange-600 border border-orange-600 rounded-lg text-xs font-semibold text-white hover:bg-orange-700 shadow-sm shadow-orange-600/20 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            Exportar Reporte
        </button>
        @endcan
    </div>
</div>