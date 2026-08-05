<!-- MODAL 1: CREAR INCIDENCIA -->
<div id="createModal" class="fixed inset-0 z-50 hidden bg-stone-900/40 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white dark:bg-stone-900 rounded-2xl max-w-lg w-full p-6 shadow-xl border border-stone-200 dark:border-stone-800">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-bold text-stone-800 dark:text-stone-100">Reportar Nueva Incidencia</h3>
            <button onclick="closeModal('createModal')" class="text-stone-400 hover:text-stone-600 dark:hover:text-stone-200 text-xl font-bold">&times;</button>
        </div>
        <form action="{{ route('admin.incidences.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-semibold text-stone-600 dark:text-stone-400 mb-1">Orden de Producción</label>
                <select name="production_order_id" required class="w-full text-sm rounded-xl bg-white dark:bg-stone-800 border-stone-200 dark:border-stone-700 text-stone-800 dark:text-stone-100 focus:border-orange-500 focus:ring-orange-500">
                    <option value="">Selecciona una Orden</option>
                    @foreach($productionOrders as $order)
                        <option value="{{ $order->id }}">#OP-{{ $order->id }} - {{ $order->product_name ?? 'Orden de producción' }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-semibold text-stone-600 dark:text-stone-400 mb-1">Título de la Incidencia</label>
                <input type="text" name="title" required placeholder="Ej. Falla en ensamble, falta de material..." class="w-full text-sm rounded-xl bg-white dark:bg-stone-800 border-stone-200 dark:border-stone-700 text-stone-800 dark:text-stone-100 placeholder-stone-400 dark:placeholder-stone-500 focus:border-orange-500 focus:ring-orange-500">
            </div>
            <div>
                <label class="block text-xs font-semibold text-stone-600 dark:text-stone-400 mb-1">Prioridad / Importancia</label>
                <select name="importance" required class="w-full text-sm rounded-xl bg-white dark:bg-stone-800 border-stone-200 dark:border-stone-700 text-stone-800 dark:text-stone-100 focus:border-orange-500 focus:ring-orange-500">
                    <option value="baja">Baja</option>
                    <option value="media">Media</option>
                    <option value="alta">Alta</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-semibold text-stone-600 dark:text-stone-400 mb-1">Descripción detallada</label>
                <textarea name="description" rows="3" required placeholder="Explica brevemente lo sucedido..." class="w-full text-sm rounded-xl bg-white dark:bg-stone-800 border-stone-200 dark:border-stone-700 text-stone-800 dark:text-stone-100 placeholder-stone-400 dark:placeholder-stone-500 focus:border-orange-500 focus:ring-orange-500"></textarea>
            </div>
            <div class="flex justify-end gap-2 pt-2">
                <button type="button" onclick="closeModal('createModal')" class="px-4 py-2 bg-stone-100 hover:bg-stone-200 dark:bg-stone-800 dark:hover:bg-stone-700 text-stone-700 dark:text-stone-300 text-xs font-medium rounded-xl transition-colors">Cancelar</button>
                <button type="submit" class="px-4 py-2 bg-orange-600 hover:bg-orange-700 text-white text-xs font-medium rounded-xl shadow-sm transition-colors">Guardar Incidencia</button>
            </div>
        </form>
    </div>
</div>