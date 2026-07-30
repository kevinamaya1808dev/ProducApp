<div id="createRecipeModal" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm flex items-center justify-center hidden z-50 p-4">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg overflow-hidden border border-slate-100">
        <div class="p-6 border-b border-slate-100 flex items-center justify-between">
            <h3 class="text-lg font-bold text-slate-900">Nueva Receta</h3>
            <button type="button" onclick="closeModal('createRecipeModal')" class="text-slate-400 hover:text-slate-600">
                &times;
            </button>
        </div>

        <form action="{{ route('recipes.store') }}" method="POST" class="p-6 space-y-4">
            @csrf
            
            <!-- Nombre de la Receta -->
            <div>
                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">Nombre de la Receta</label>
                <input type="text" name="name" required placeholder="Ej. Mezcla Base / Ensamble Principal" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600">
            </div>

            <!-- Producto Asociado -->
            <div>
                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">Producto Asociado</label>
                <select name="product_id" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600">
                    <option value="">Selecciona un producto...</option>
                    @foreach($products as $product)
                        <option value="{{ $product->id }}">{{ $product->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Instrucciones -->
            <div>
                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">Instrucciones / Procedimiento</label>
                <textarea name="instructions" rows="4" placeholder="Describe los pasos o detalles de fabricación..." class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600"></textarea>
            </div>

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                <button type="button" onclick="closeModal('createRecipeModal')" class="px-4 py-2 text-slate-600 text-sm font-medium hover:bg-slate-100 rounded-xl transition-all">
                    Cancelar
                </button>
                <button type="submit" class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium text-sm rounded-xl transition-all shadow-md shadow-blue-600/20">
                    Guardar Receta
                </button>
            </div>
        </form>
    </div>
</div>