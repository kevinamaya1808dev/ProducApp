<div id="createCategoryModal" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 dark:bg-slate-900/85 backdrop-blur-sm hidden">
    <div class="bg-white dark:bg-stone-900 rounded-2xl shadow-xl w-full max-w-lg mx-4 overflow-hidden border border-slate-100 dark:border-stone-800">
        <div class="px-6 py-4 border-b border-slate-100 dark:border-stone-800 bg-slate-50/50 dark:bg-stone-800/50 flex items-center justify-between">
            <h3 class="font-bold text-slate-900 dark:text-stone-100 text-lg">Crear Nueva Categoría</h3>
            <button type="button" onclick="closeModal('createCategoryModal')" class="text-slate-400 dark:text-stone-400 hover:text-slate-600 dark:hover:text-stone-200 transition-colors">&times;</button>
        </div>
        <form action="{{ route('categories.store') }}" method="POST">
            @csrf
            <div class="p-6 space-y-4">
                <div>
                    <label class="block text-xs font-bold text-slate-600 dark:text-stone-300 uppercase mb-1">Nombre de la Categoría</label>
                    <input type="text" name="name" placeholder="Ej. Electrónica" required class="w-full bg-white dark:bg-stone-800 border border-slate-200 dark:border-stone-700 rounded-xl px-3 py-2 text-sm text-slate-700 dark:text-stone-100 placeholder-slate-400 dark:placeholder-stone-500 focus:ring-2 focus:ring-orange-500/20 focus:border-orange-600 dark:focus:border-orange-500 outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-600 dark:text-stone-300 uppercase mb-1">Descripción</label>
                    <textarea name="description" rows="3" placeholder="Descripción breve..." class="w-full bg-white dark:bg-stone-800 border border-slate-200 dark:border-stone-700 rounded-xl px-3 py-2 text-sm text-slate-700 dark:text-stone-100 placeholder-slate-400 dark:placeholder-stone-500 focus:ring-2 focus:ring-orange-500/20 focus:border-orange-600 dark:focus:border-orange-500 outline-none"></textarea>
                </div>
            </div>
            <div class="px-6 py-4 bg-slate-50 dark:bg-stone-800/50 border-t border-slate-100 dark:border-stone-800 flex justify-end gap-2">
                <button type="button" onclick="closeModal('createCategoryModal')" class="px-4 py-2 bg-white dark:bg-stone-800 border border-slate-200 dark:border-stone-700 text-slate-700 dark:text-stone-300 text-sm font-medium rounded-xl hover:bg-slate-50 dark:hover:bg-stone-700 transition-colors">Cancelar</button>
                <button type="submit" class="px-4 py-2 bg-orange-600 text-white text-sm font-medium rounded-xl hover:bg-orange-700 shadow-md shadow-orange-600/20 transition-colors">Guardar Categoría</button>
            </div>
        </form>
    </div>
</div>