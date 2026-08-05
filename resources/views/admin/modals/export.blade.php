<div id="export-modal" class="fixed inset-0 z-50 hidden bg-slate-900/50 dark:bg-slate-900/85 backdrop-blur-sm flex items-center justify-center p-4 overflow-y-auto">
    <div class="bg-white dark:bg-stone-900 rounded-xl shadow-xl max-w-md w-full overflow-hidden border border-slate-100 dark:border-stone-800 animate-in fade-in zoom-in duration-200">
        <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100 dark:border-stone-800 bg-slate-50/50 dark:bg-stone-800/50">
            <h3 class="text-sm font-bold text-slate-900 dark:text-stone-100">Exportar Datos de Producción</h3>
            <button type="button" onclick="closeModal('export-modal')" class="p-1.5 text-slate-400 dark:text-stone-400 hover:text-slate-600 dark:hover:text-stone-200 hover:bg-slate-100 dark:hover:bg-stone-800 rounded-lg transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        <form id="dashboard-export-form" onsubmit="handleDashboardExport(event)" class="p-6 space-y-4">
            <div>
                <label class="block text-xs font-semibold text-slate-700 dark:text-stone-300 mb-1">Formato de Archivo</label>
                <select id="export-format-select" name="format" class="w-full text-xs border border-slate-200 dark:border-stone-700 rounded-lg p-2.5 bg-slate-50 dark:bg-stone-800 text-slate-700 dark:text-stone-100 focus:ring-2 focus:ring-orange-500 dark:focus:ring-orange-500 focus:outline-none">
                    <option value="excel" class="dark:bg-stone-800">Excel (.xlsx)</option>
                    <option value="pdf" class="dark:bg-stone-800">Documento PDF (.pdf)</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-700 dark:text-stone-300 mb-1">Incluir Métricas de</label>
                <div class="space-y-2 mt-2">
                    <label class="flex items-center gap-2 text-xs text-slate-600 dark:text-stone-400 cursor-pointer">
                        <input type="checkbox" checked name="sections[]" value="orders" class="rounded border-slate-300 dark:border-stone-700 bg-white dark:bg-stone-800 text-orange-600 focus:ring-orange-500"> Órdenes de producción
                    </label>
                    <label class="flex items-center gap-2 text-xs text-slate-600 dark:text-stone-400 cursor-pointer">
                        <input type="checkbox" checked name="sections[]" value="kpis" class="rounded border-slate-300 dark:border-stone-700 bg-white dark:bg-stone-800 text-orange-600 focus:ring-orange-500"> KPIs y Rendimiento
                    </label>
                </div>
            </div>
            <div class="flex justify-end gap-3 pt-4 border-t border-slate-100 dark:border-stone-800">
                <button type="button" onclick="closeModal('export-modal')" class="px-4 py-2 border border-slate-200 dark:border-stone-700 rounded-lg text-xs font-semibold text-slate-600 dark:text-stone-300 hover:bg-slate-50 dark:hover:bg-stone-800 transition-colors">Cancelar</button>
                <button type="submit" class="px-4 py-2 bg-orange-600 text-white rounded-lg text-xs font-semibold hover:bg-orange-700 shadow-sm transition-colors">Descargar Reporte</button>
            </div>
        </form>
    </div>
</div>

<script>
    function handleDashboardExport(event) {
        event.preventDefault();
        const format = document.getElementById('export-format-select').value;

        const routes = {
            'excel': "{{ route('admin.export.dashboard.excel') }}",
            'pdf': "{{ route('admin.export.dashboard.pdf') }}"
        };

        if (routes[format]) {
            closeModal('export-modal');
            window.location.href = routes[format];
        }
    }
</script>