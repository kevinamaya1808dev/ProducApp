{{-- Encabezado: título + botones Modificar / Nuevo Permiso --}}
<div class="flex items-center justify-between mb-6">
    <h1 class="text-xl font-bold text-slate-900 dark:text-stone-100">Gestión de Permisos</h1>

    @can('users.manage')
        <div class="flex items-center gap-2">
            <button type="button" id="manageModeBtn" onclick="toggleManageMode()"
                    class="px-3 py-2 bg-white dark:bg-stone-800 border border-slate-200 dark:border-stone-700 text-slate-600 dark:text-stone-300 text-xs font-semibold rounded-lg hover:bg-slate-50 dark:hover:bg-stone-700 transition-colors">
                Modificar
            </button>
            <button type="button" onclick="openCreateModal()"
                    class="px-4 py-2 bg-orange-600 text-white text-sm font-medium rounded-xl hover:bg-orange-700 shadow-md shadow-orange-600/20 transition-colors">
                Nuevo Permiso
            </button>
        </div>
    @endcan
</div>