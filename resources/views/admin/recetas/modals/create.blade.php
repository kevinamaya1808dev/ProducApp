<div id="createRecipeModal" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 backdrop-blur-sm hidden">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg mx-4 overflow-hidden border border-slate-100">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
            <h3 class="font-bold text-slate-900 text-lg">Crear Nueva Receta</h3>
            <button type="button" onclick="closeModal('createRecipeModal')" class="text-slate-400 hover:text-slate-600">&times;</button>
        </div>
        <form action="{{ route('recipes.store') }}" method="POST">
            @csrf
            <div class="p-6 space-y-4">
                <div>
                    <label class="block text-xs font-bold text-slate-600 uppercase mb-1">Nombre de la Receta</label>
                    <input type="text" name="name" placeholder="Ej. Mezcla Base A" required class="w-full bg-white border border-slate-200 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-600 uppercase mb-1">Código / SKU</label>
                    <input type="text" name="code" placeholder="Ej. REC-001" class="w-full bg-white border border-slate-200 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-600 uppercase mb-1">Descripción / Instrucciones</label>
                    <textarea name="description" rows="3" placeholder="Instrucciones o descripción breve..." class="w-full bg-white border border-slate-200 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600"></textarea>
                </div>
            </div>
            <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex justify-end gap-2">
                <button type="button" onclick="closeModal('createRecipeModal')" class="px-4 py-2 bg-white border border-slate-200 text-slate-700 text-sm font-medium rounded-xl hover:bg-slate-50">Cancelar</button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-xl hover:bg-blue-700 shadow-md shadow-blue-600/20">Guardar Receta</button>
            </div>
        </form>
    </div>
</div>