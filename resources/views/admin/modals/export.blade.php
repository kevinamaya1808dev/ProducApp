<div id="export-modal" class="fixed inset-0 z-50 hidden bg-slate-900/50 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-xl max-w-md w-full overflow-hidden border border-slate-100">
        <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100">
            <h3 class="text-sm font-bold text-slate-900">Exportar Datos de Producción</h3>
            <button type="button" onclick="closeModal('export-modal')" class="text-slate-400 hover:text-slate-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        <form method="POST" action="#" class="p-6 space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Formato de Archivo</label>
                <select name="format" class="w-full text-xs border border-slate-200 rounded-lg p-2.5 bg-slate-50 focus:ring-2 focus:ring-blue-500">
                    <option value="excel">Excel (.xlsx)</option>
                    <option value="pdf">Documento PDF (.pdf)</option>
                    <option value="csv">Valores Separados por Comas (.csv)</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Incluir Métricas de</label>
                <div class="space-y-2 mt-2">
                    <label class="flex items-center gap-2 text-xs text-slate-600"><input type="checkbox" checked name="sections[]" value="orders" class="rounded border-slate-300 text-blue-600"> Órdenes de producción</label>
                    <label class="flex items-center gap-2 text-xs text-slate-600"><input type="checkbox" checked name="sections[]" value="kpis" class="rounded border-slate-300 text-blue-600"> KPIs y Rendimiento</label>
                </div>
            </div>
            <div class="flex justify-end gap-3 pt-4 border-t border-slate-100">
                <button type="button" onclick="closeModal('export-modal')" class="px-4 py-2 border border-slate-200 rounded-lg text-xs font-semibold text-slate-600 hover:bg-slate-50">Cancelar</button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg text-xs font-semibold hover:bg-blue-700">Descargar Reporte</button>
            </div>
        </form>
    </div>
</div>