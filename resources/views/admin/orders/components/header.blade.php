<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
    <div>
        <h1 class="text-2xl font-bold text-slate-900 dark:text-stone-100 tracking-tight">Órdenes de Producción</h1>
        <p class="text-sm text-slate-500 dark:text-stone-400 mt-1">Gestión completa de órdenes &middot; {{ $orders->total() }} registro(s)</p>
    </div>

    @can('manage-orders')
    <button type="button" onclick="openCreateModal()" class="bg-orange-600 hover:bg-orange-700 text-white px-5 py-2.5 rounded-lg text-sm font-semibold transition-all shadow-sm shadow-orange-600/30 flex items-center gap-2">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
        Nueva Orden
    </button>
    @endcan
</div>