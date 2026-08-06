<div class="bg-white dark:bg-stone-900 p-4 rounded-2xl border border-slate-200 dark:border-stone-800 shadow-sm mb-6 flex flex-col md:flex-row justify-between items-center gap-4">
    <div class="w-full md:w-96 relative">
        <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-slate-400">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
        </span>
        <input type="text" id="searchInput" onkeyup="filterOrders()" placeholder="Buscar por orden, producto u operario..." class="w-full pl-10 pr-4 py-2 bg-slate-50 dark:bg-stone-800 border border-slate-200 dark:border-stone-700 rounded-xl text-sm text-slate-800 dark:text-stone-200 outline-none focus:ring-2 focus:ring-orange-500/50">
    </div>

    <div class="flex flex-wrap items-center gap-2 w-full md:w-auto">
        <button type="button" onclick="setStatusFilter('all', this)" class="status-filter-btn px-3 py-1.5 rounded-xl text-xs font-bold bg-orange-600 text-white shadow-sm shadow-orange-600/20 transition-all">Todos</button>
        <button type="button" onclick="setStatusFilter('pending', this)" class="status-filter-btn px-3 py-1.5 rounded-xl text-xs font-bold bg-white dark:bg-stone-900 border border-slate-200 dark:border-stone-800 text-slate-600 dark:text-stone-300 hover:bg-slate-50 dark:hover:bg-stone-800 transition-all">Pendientes</button>
        <button type="button" onclick="setStatusFilter('in_progress', this)" class="status-filter-btn px-3 py-1.5 rounded-xl text-xs font-bold bg-white dark:bg-stone-900 border border-slate-200 dark:border-stone-800 text-slate-600 dark:text-stone-300 hover:bg-slate-50 dark:hover:bg-stone-800 transition-all">En Progreso</button>
        <button type="button" onclick="setStatusFilter('completed', this)" class="status-filter-btn px-3 py-1.5 rounded-xl text-xs font-bold bg-white dark:bg-stone-900 border border-slate-200 dark:border-stone-800 text-slate-600 dark:text-stone-300 hover:bg-slate-50 dark:hover:bg-stone-800 transition-all">Completadas</button>
        <button type="button" onclick="setStatusFilter('cancelled', this)" class="status-filter-btn px-3 py-1.5 rounded-xl text-xs font-bold bg-white dark:bg-stone-900 border border-slate-200 dark:border-stone-800 text-slate-600 dark:text-stone-300 hover:bg-slate-50 dark:hover:bg-stone-800 transition-all">Canceladas</button>
    </div>
</div>