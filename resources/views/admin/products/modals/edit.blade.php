<div id="modal-edit" class="fixed inset-0 z-50 hidden bg-slate-900/50 backdrop-blur-sm overflow-y-auto items-center justify-center p-4">
    <div class="relative bg-white rounded-xl shadow-xl w-full max-w-2xl transform transition-all">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
            <h3 class="text-lg font-bold text-slate-900">Editar Producto</h3>
            <button onclick="closeModal('modal-edit')" type="button" class="text-slate-400 hover:text-slate-500">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>

        <form id="edit_product_form" method="POST">
            @csrf
            @method('PUT')

            <div class="px-6 py-4 space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="col-span-2 md:col-span-1">
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Nombre del Producto *</label>
                        <input type="text" id="edit_name" name="name" required class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-orange-500">
                    </div>

                    <div class="col-span-2 md:col-span-1">
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Código único *</label>
                        <input type="text" id="edit_code" name="code" required class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-orange-500">
                    </div>

                    <div class="col-span-2 md:col-span-1">
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Categoría *</label>
                        <select id="edit_category_id" name="category_id" required class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-orange-500">
                            <option value="">Seleccione una categoría...</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-span-2 md:col-span-1">
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Costo Unitario *</label>
                        <input type="number" id="edit_unit_cost" step="0.01" min="0" name="unit_cost" required class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-orange-500">
                    </div>

                    <div class="col-span-2">
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Stock *</label>
                        <input type="number" id="edit_stock" name="stock" min="0" required class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-orange-500">
                    </div>

                    <div class="col-span-2">
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Descripción</label>
                        <textarea id="edit_description" name="description" rows="3" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-orange-500"></textarea>
                    </div>
                </div>
            </div>

            <div class="px-6 py-4 bg-slate-50 rounded-b-xl border-t border-slate-100 flex justify-end gap-3">
                <button type="button" onclick="closeModal('modal-edit')" class="px-4 py-2 text-xs font-semibold text-slate-600 bg-white border border-slate-200 rounded-lg hover:bg-slate-50 transition-colors">Cancelar</button>
                <button type="submit" class="px-4 py-2 text-xs font-semibold text-white bg-orange-600 rounded-lg hover:bg-orange-700 transition-colors">Actualizar Cambios</button>
            </div>
        </form>
    </div>
</div>