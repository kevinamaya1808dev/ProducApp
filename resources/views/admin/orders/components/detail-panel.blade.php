<div id="orderPanel" class="hidden fixed inset-y-0 right-0 z-40 w-full max-w-md bg-white dark:bg-stone-900 border-l border-slate-200 dark:border-stone-800 shadow-2xl flex-col transition-transform duration-300" style="display:none;">
    <div class="px-6 py-4 border-b border-slate-100 dark:border-stone-800 flex justify-between items-center bg-slate-50 dark:bg-stone-800/50">
        <div>
            <span id="panelCategory" class="text-[10px] font-bold uppercase tracking-wider text-orange-600 dark:text-orange-400 bg-orange-50 dark:bg-orange-500/10 px-2 py-0.5 rounded">Categoría</span>
            <h3 id="panelOrderNumber" class="text-lg font-bold text-slate-900 dark:text-stone-100 mt-0.5">ORD-0000</h3>
        </div>
        <button type="button" onclick="closePanel()" class="text-slate-400 hover:text-slate-600 dark:hover:text-stone-200">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>
    </div>

    <div class="p-6 space-y-6 flex-1 overflow-y-auto text-sm">
        <div class="bg-slate-50 dark:bg-stone-800/50 p-4 rounded-2xl border border-slate-100 dark:border-stone-800 space-y-3">
            <div>
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Producto a Fabricar</p>
                <h4 id="panelProduct" class="font-bold text-slate-800 dark:text-stone-200 text-base">Nombre del Producto</h4>
            </div>
            <div>
                <div class="flex justify-between text-xs font-bold text-slate-600 dark:text-stone-400 mb-1">
                    <span>Progreso General</span>
                    <span id="panelProgress">0%</span>
                </div>
                <div class="w-full bg-slate-200 dark:bg-stone-700 h-2 rounded-full overflow-hidden">
                    <div id="panelProgressBar" class="bg-orange-600 h-full rounded-full transition-all duration-300" style="width: 0%"></div>
                </div>
                <p id="panelProgressText" class="text-right text-[11px] text-slate-400 mt-1">0 / 0 pzas</p>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div class="p-3 rounded-xl bg-slate-50 dark:bg-stone-800/50 border border-slate-100 dark:border-stone-800">
                <span class="text-xs text-slate-400 block">Estado</span>
                <span id="panelStatus" class="font-bold text-slate-800 dark:text-stone-200">-</span>
            </div>
            <div class="p-3 rounded-xl bg-slate-50 dark:bg-stone-800/50 border border-slate-100 dark:border-stone-800">
                <span class="text-xs text-slate-400 block">Prioridad</span>
                <span id="panelPriority" class="font-bold text-slate-800 dark:text-stone-200">-</span>
            </div>
            <div class="p-3 rounded-xl bg-slate-50 dark:bg-stone-800/50 border border-slate-100 dark:border-stone-800">
                <span class="text-xs text-slate-400 block">Operario Principal</span>
                <span id="panelOperator" class="font-bold text-slate-800 dark:text-stone-200">-</span>
            </div>
            <div class="p-3 rounded-xl bg-slate-50 dark:bg-stone-800/50 border border-slate-100 dark:border-stone-800">
                <span class="text-xs text-slate-400 block">Estación</span>
                <span id="panelStation" class="font-bold text-slate-800 dark:text-stone-200">-</span>
            </div>
            <div class="col-span-2 p-3 rounded-xl bg-slate-50 dark:bg-stone-800/50 border border-slate-100 dark:border-stone-800">
                <span class="text-xs text-slate-400 block">Fecha Límite</span>
                <span id="panelDeadline" class="font-bold text-slate-800 dark:text-stone-200">-</span>
            </div>
        </div>

        <div>
            <div class="flex justify-between items-center mb-3">
                <h4 class="font-bold text-slate-800 dark:text-stone-200">Desglose de Procesos (<span id="panelSubOrdersCount">0</span>)</h4>
            </div>
            <div id="panelSubOrdersList" class="space-y-2">
                <!-- Inyectado vía JS -->
            </div>
        </div>
    </div>

    @can('manage-orders')
    <div class="px-6 py-4 bg-slate-50 dark:bg-stone-800/50 border-t border-slate-100 dark:border-stone-800 flex justify-end gap-3">
        <button type="button" onclick="openDeleteModalFromPanel()" class="px-4 py-2 bg-red-50 dark:bg-red-500/10 text-red-600 dark:text-red-400 rounded-xl text-sm font-medium hover:bg-red-100 transition-colors">Eliminar</button>
        <button type="button" onclick="openEditModalFromPanel()" class="px-4 py-2 bg-orange-600 text-white rounded-xl text-sm font-medium hover:bg-orange-700 shadow-sm shadow-orange-600/30 transition-colors">Editar Orden</button>
    </div>
    @endcan
</div>