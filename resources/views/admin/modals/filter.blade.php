<div id="filter-modal" class="fixed inset-0 z-50 hidden bg-slate-900/50 backdrop-blur-sm flex items-center justify-center p-4 overflow-y-auto">
    <div class="bg-white rounded-xl shadow-xl max-w-md w-full overflow-hidden border border-slate-100 animate-in fade-in zoom-in duration-200">
        <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100">
            <h3 class="text-sm font-bold text-slate-900">Filtrar Resumen de Producción</h3>
            <button type="button" onclick="closeModal('filter-modal')" class="p-1.5 text-slate-400 hover:text-slate-600 hover:bg-slate-100 rounded-lg transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        <form method="GET" action="{{ route('admin.dashboard') }}" class="p-6 space-y-4">
            @if(request('search'))
                <input type="hidden" name="search" value="{{ request('search') }}">
            @endif

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Estado de Orden</label>
                <select name="status" class="w-full text-xs border border-slate-200 rounded-lg p-2.5 bg-slate-50 focus:ring-2 focus:ring-orange-500 focus:outline-none">
                    <option value="">Todos los estados</option>
                    <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>En Progreso</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completada</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Iniciando / Pendiente</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Fecha de Creación</label>
                <input type="date" name="date" value="{{ request('date') }}" class="w-full text-xs border border-slate-200 rounded-lg p-2.5 bg-slate-50 focus:ring-2 focus:ring-orange-500 focus:outline-none">
            </div>
            <div class="flex items-center justify-between pt-4 border-t border-slate-100">
                @if(request('status') || request('date'))
                    <a href="{{ route('admin.dashboard') }}" class="text-xs text-rose-600 font-semibold hover:underline">Limpiar Filtros</a>
                @else
                    <div></div>
                @endif
                <div class="flex gap-3">
                    <button type="button" onclick="closeModal('filter-modal')" class="px-4 py-2 border border-slate-200 rounded-lg text-xs font-semibold text-slate-600 hover:bg-slate-50 transition-colors">Cancelar</button>
                    <button type="submit" class="px-4 py-2 bg-orange-600 text-white rounded-lg text-xs font-semibold hover:bg-orange-700 shadow-sm transition-colors">Aplicar Filtros</button>
                </div>
            </div>
        </form>
    </div>
</div>