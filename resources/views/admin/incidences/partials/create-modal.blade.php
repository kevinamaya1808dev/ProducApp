<!-- MODAL 1: CREAR INCIDENCIA -->
<div id="createModal" class="fixed inset-0 z-50 hidden bg-stone-900/50 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white dark:bg-stone-900 rounded-2xl max-w-lg w-full shadow-2xl shadow-stone-900/10 dark:shadow-black/40 border border-stone-200 dark:border-stone-800 overflow-hidden">

        <!-- Encabezado -->
        <div class="flex items-start justify-between gap-4 px-6 pt-6 pb-4 border-b border-stone-100 dark:border-stone-800">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-orange-50 dark:bg-orange-950/50 border border-orange-200/80 dark:border-orange-900/50 flex items-center justify-center text-orange-600 dark:text-orange-400 shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-stone-800 dark:text-stone-100 leading-tight">Reportar Nueva Incidencia</h3>
                    <p class="text-xs text-stone-500 dark:text-stone-400 mt-0.5">Registra un problema detectado en producción</p>
                </div>
            </div>
            <button type="button" onclick="closeModal('createModal')" class="shrink-0 w-8 h-8 flex items-center justify-center text-stone-400 hover:text-stone-600 dark:hover:text-stone-200 hover:bg-stone-100 dark:hover:bg-stone-800 rounded-lg transition-colors cursor-pointer">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>

        <form action="{{ route('admin.incidences.store') }}" method="POST" class="px-6 py-5 space-y-5">
            @csrf

            <div>
                <label class="block text-xs font-semibold text-stone-600 dark:text-stone-400 mb-1.5">Orden de Producción</label>
                <select name="production_order_id" required class="w-full text-sm py-3 px-3.5 rounded-xl bg-stone-50 dark:bg-stone-800 border-stone-200 dark:border-stone-700 text-stone-800 dark:text-stone-100 focus:border-orange-500 focus:ring-orange-500 focus:bg-white dark:focus:bg-stone-800 transition-colors">
                    <option value="">Selecciona una Orden</option>
                    @foreach($productionOrders as $order)
                        <option value="{{ $order->id }}">#OP-{{ $order->id }} - {{ $order->product_name ?? 'Orden de producción' }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-semibold text-stone-600 dark:text-stone-400 mb-1.5">Título de la Incidencia</label>
                <input type="text" name="title" required placeholder="Ej. Falla en ensamble, falta de material..." class="w-full text-sm py-3 px-3.5 rounded-xl bg-stone-50 dark:bg-stone-800 border-stone-200 dark:border-stone-700 text-stone-800 dark:text-stone-100 placeholder-stone-400 dark:placeholder-stone-500 focus:border-orange-500 focus:ring-orange-500 focus:bg-white dark:focus:bg-stone-800 transition-colors">
            </div>

            <div>
                <label class="block text-xs font-semibold text-stone-600 dark:text-stone-400 mb-1.5">Prioridad / Importancia</label>
                <select name="importance" required class="w-full text-sm py-3 px-3.5 rounded-xl bg-stone-50 dark:bg-stone-800 border-stone-200 dark:border-stone-700 text-stone-800 dark:text-stone-100 focus:border-orange-500 focus:ring-orange-500 focus:bg-white dark:focus:bg-stone-800 transition-colors">
                    <option value="baja">Baja</option>
                    <option value="media">Media</option>
                    <option value="alta">Alta</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-semibold text-stone-600 dark:text-stone-400 mb-1.5">Descripción detallada</label>
                <textarea name="description" rows="4" required placeholder="Explica brevemente lo sucedido..." class="w-full text-sm py-3 px-3.5 rounded-xl bg-stone-50 dark:bg-stone-800 border-stone-200 dark:border-stone-700 text-stone-800 dark:text-stone-100 placeholder-stone-400 dark:placeholder-stone-500 focus:border-orange-500 focus:ring-orange-500 focus:bg-white dark:focus:bg-stone-800 transition-colors resize-none"></textarea>
            </div>

            <div class="flex justify-end gap-2 pt-2">
                <button type="button" onclick="closeModal('createModal')" class="px-4 py-2.5 bg-stone-100 hover:bg-stone-200 dark:bg-stone-800 dark:hover:bg-stone-700 text-stone-700 dark:text-stone-300 text-xs font-semibold rounded-xl transition-colors cursor-pointer">Cancelar</button>
                <button type="submit" class="flex items-center gap-1.5 px-4 py-2.5 bg-orange-600 hover:bg-orange-700 text-white text-xs font-semibold rounded-xl shadow-sm shadow-orange-500/20 hover:shadow-md hover:shadow-orange-500/30 transition-all cursor-pointer">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
                    Guardar Incidencia
                </button>
            </div>
        </form>
    </div>
</div>