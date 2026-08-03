<!-- Panel Lateral -->
<div id="orderPanel" class="w-full max-w-[340px] shrink-0 bg-white border border-slate-200 rounded-2xl shadow-sm hidden lg:flex flex-col relative" style="display:none;">

    <div class="p-5 border-b border-slate-100 flex justify-between items-center bg-slate-50/50 rounded-t-2xl">
        <h3 class="font-bold text-slate-900 text-sm">Detalle de Orden</h3>
        <button type="button" onclick="closePanel()" class="text-slate-400 hover:text-slate-600">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>
    </div>

    <div class="p-5 flex-1 overflow-y-auto">
        <span id="panelOrderNumber" class="bg-orange-50 text-orange-600 border border-orange-100 text-[10px] font-bold px-2 py-0.5 rounded-md mb-3 inline-block"></span>
        <h2 id="panelProduct" class="text-lg font-black text-slate-900 leading-tight"></h2>
        <p id="panelCategory" class="text-xs text-slate-400 mt-1"></p>

        <div class="my-8 text-center bg-slate-50 p-6 rounded-xl border border-slate-100">
            <div id="panelProgress" class="text-5xl font-black text-slate-900 tracking-tighter">0%</div>
            <div class="w-full h-2 bg-slate-200 rounded-full overflow-hidden mt-4 mb-2">
                <div id="panelProgressBar" class="h-full bg-orange-600 rounded-full transition-all duration-500" style="width: 0%"></div>
            </div>
            <div id="panelProgressText" class="text-xs font-bold text-slate-500 tracking-widest"></div>
        </div>

        <ul class="space-y-4">
            <li class="flex justify-between items-center text-sm border-b border-slate-50 pb-3">
                <span class="text-slate-400 font-semibold text-xs tracking-wider uppercase">Estado</span>
                <span id="panelStatus" class="text-slate-800 font-bold"></span>
            </li>
            <li class="flex justify-between items-center text-sm border-b border-slate-50 pb-3">
                <span class="text-slate-400 font-semibold text-xs tracking-wider uppercase">Prioridad</span>
                <span id="panelPriority" class="text-slate-800 font-bold"></span>
            </li>
            <li class="flex justify-between items-center text-sm border-b border-slate-50 pb-3">
                <span class="text-slate-400 font-semibold text-xs tracking-wider uppercase">Operario</span>
                <span id="panelOperator" class="text-slate-800 font-bold"></span>
            </li>
            <li class="flex justify-between items-center text-sm border-b border-slate-50 pb-3">
                <span class="text-slate-400 font-semibold text-xs tracking-wider uppercase">Estación</span>
                <span id="panelStation" class="text-slate-800 font-bold"></span>
            </li>
            <li class="flex justify-between items-center text-sm">
                <span class="text-slate-400 font-semibold text-xs tracking-wider uppercase">Fecha Límite</span>
                <span id="panelDeadline" class="text-slate-800 font-bold"></span>
            </li>
        </ul>
    </div>

    <div class="p-4 border-t border-slate-100 bg-slate-50/50 rounded-b-2xl flex gap-2">
        @can('manage-orders')
        <button type="button" onclick="openEditModalFromPanel()" class="flex-1 bg-orange-600 hover:bg-orange-700 text-white font-semibold text-sm py-2.5 rounded-lg transition-colors shadow-sm">
            Editar
        </button>
        <button type="button" onclick="openDeleteModalFromPanel()" class="bg-red-50 hover:bg-red-100 text-red-600 font-semibold text-sm py-2.5 px-4 rounded-lg transition-colors shadow-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
        </button>
        @else
        <div class="text-center text-xs text-slate-400 py-1 w-full">Modo visualización (Sin privilegios de edición)</div>
        @endcan
    </div>
</div>