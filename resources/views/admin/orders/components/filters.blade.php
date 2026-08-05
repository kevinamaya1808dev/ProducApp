<div class="flex flex-col lg:flex-row gap-4 mb-6 items-start lg:items-center">
    <div class="bg-white dark:bg-stone-900 p-2.5 rounded-xl border border-slate-200 dark:border-stone-800 shadow-sm relative w-full lg:w-96 shrink-0">
        <svg class="w-4 h-4 text-slate-400 dark:text-stone-500 absolute left-4 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
        <input type="text" id="searchInput" oninput="filterOrders()" placeholder="Buscar por ID, producto u operario..." class="w-full pl-8 pr-2 text-sm bg-transparent border-none focus:ring-0 text-slate-700 dark:text-stone-200 placeholder-slate-400 dark:placeholder-stone-500 outline-none">
    </div>

    <div class="flex flex-wrap gap-2" id="statusFilters">
        <button type="button" onclick="setStatusFilter('all', this)" class="status-filter-btn px-4 py-1.5 rounded-lg text-sm font-medium bg-orange-600 text-white shadow-sm shadow-orange-600/20">Todos</button>
        <button type="button" onclick="setStatusFilter('pending', this)" class="status-filter-btn px-4 py-1.5 rounded-lg text-sm font-medium bg-white dark:bg-stone-900 border border-slate-200 dark:border-stone-800 text-slate-600 dark:text-stone-300 hover:bg-slate-50 dark:hover:bg-stone-800 transition-colors">Pendiente</button>
        <button type="button" onclick="setStatusFilter('in_progress', this)" class="status-filter-btn px-4 py-1.5 rounded-lg text-sm font-medium bg-white dark:bg-stone-900 border border-slate-200 dark:border-stone-800 text-slate-600 dark:text-stone-300 hover:bg-slate-50 dark:hover:bg-stone-800 transition-colors">En Progreso</button>
        <button type="button" onclick="setStatusFilter('completed', this)" class="status-filter-btn px-4 py-1.5 rounded-lg text-sm font-medium bg-white dark:bg-stone-900 border border-slate-200 dark:border-stone-800 text-slate-600 dark:text-stone-300 hover:bg-slate-50 dark:hover:bg-stone-800 transition-colors">Completada</button>
        <button type="button" onclick="setStatusFilter('cancelled', this)" class="status-filter-btn px-4 py-1.5 rounded-lg text-sm font-medium bg-white dark:bg-stone-900 border border-slate-200 dark:border-stone-800 text-slate-600 dark:text-stone-300 hover:bg-slate-50 dark:hover:bg-stone-800 transition-colors">Cancelada</button>
    </div>
</div>