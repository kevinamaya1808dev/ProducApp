<div id="deleteCategoryModal-{{ $category->id }}" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 dark:bg-slate-900/85 backdrop-blur-sm hidden">
    <div class="bg-white dark:bg-stone-900 rounded-2xl shadow-xl w-full max-w-md mx-4 overflow-hidden border border-slate-100 dark:border-stone-800">
        <div class="px-6 py-4 border-b border-slate-100 dark:border-stone-800 bg-slate-50/50 dark:bg-stone-800/50 flex items-center justify-between">
            <h3 class="font-bold text-red-600 dark:text-red-400 text-lg flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
                Eliminar Categoría
            </h3>
            <button type="button" onclick="closeModal('deleteCategoryModal-{{ $category->id }}')" class="text-slate-400 dark:text-stone-400 hover:text-slate-600 dark:hover:text-stone-200 transition-colors">&times;</button>
        </div>
        <form action="{{ route('categories.destroy', $category->id) }}" method="POST">
            @csrf
            @method('DELETE')
            <div class="p-6 space-y-3">
                <p class="text-sm text-slate-600 dark:text-stone-300">
                    ¿Estás seguro de que deseas eliminar la categoría <strong class="text-slate-900 dark:text-stone-100">{{ $category->name }}</strong>?
                </p>
                <p class="text-xs text-amber-600 dark:text-amber-400 bg-amber-50 dark:bg-amber-950/40 border border-amber-200/60 dark:border-amber-900/50 p-3 rounded-xl">
                    Esta acción no se puede deshacer y podría afectar a los productos asociados si no se reasignen previamente.
                </p>
            </div>
            <div class="px-6 py-4 bg-slate-50 dark:bg-stone-800/50 border-t border-slate-100 dark:border-stone-800 flex justify-end gap-2">
                <button type="button" onclick="closeModal('deleteCategoryModal-{{ $category->id }}')" class="px-4 py-2 bg-white dark:bg-stone-800 border border-slate-200 dark:border-stone-700 text-slate-700 dark:text-stone-300 text-sm font-medium rounded-xl hover:bg-slate-50 dark:hover:bg-stone-700 transition-colors">Cancelar</button>
                <button type="submit" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-xl shadow-md shadow-red-600/20 transition-colors">Sí, eliminar</button>
            </div>
        </form>
    </div>
</div>