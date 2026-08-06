<div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
    <div>
        <h1 class="text-2xl font-black text-slate-900 dark:text-stone-100 tracking-tight">Órdenes de Producción</h1>
        <p class="text-sm text-slate-500 dark:text-stone-400">Administra y supervisa las órdenes de fabricación y sus procesos internos.</p>
    </div>
    @can('manage-orders')
    <button type="button" onclick="openCreateModal()" class="px-4 py-2.5 bg-orange-600 hover:bg-orange-700 text-white font-medium text-sm rounded-xl shadow-lg shadow-orange-600/20 transition-all flex items-center gap-2">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
        Nueva Orden
    </button>
    @endcan
</div>