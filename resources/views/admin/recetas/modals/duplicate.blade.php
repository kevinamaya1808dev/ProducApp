@props(['recipe'])
<div id="duplicateRecipeModal-{{ $recipe->id }}" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm transition-opacity duration-300">
    <div class="bg-white dark:bg-stone-900 rounded-2xl shadow-2xl w-full max-w-md border border-slate-200/80 dark:border-stone-800 overflow-hidden transform transition-all">
        
        <!-- Header -->
        <div class="px-6 py-4 border-b border-slate-100 dark:border-stone-800 flex justify-between items-center">
            <h3 class="text-lg font-bold text-slate-900 dark:text-stone-100">Duplicar Receta</h3>
            <button type="button" onclick="closeModal('duplicateRecipeModal-{{ $recipe->id }}')" class="text-slate-400 hover:text-slate-600 dark:text-stone-500 dark:hover:text-stone-300">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>

        <!-- Form -->
        <form action="{{ route('recipes.duplicate', $recipe->id) }}" method="POST" class="p-6 space-y-4">
            @csrf
            <input type="hidden" name="form_source" value="duplicateRecipeModal-{{ $recipe->id }}">

            <div>
                <p class="text-sm text-slate-600 dark:text-stone-300">
                    ¿Estás seguro de que deseas duplicar la receta <strong class="text-slate-900 dark:text-stone-100">"{{ $recipe->name }}"</strong>? Se creará una copia exacta incluyendo todos sus componentes.
                </p>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-600 dark:text-stone-400 mb-1">Nuevo Nombre de la Receta</label>
                <input type="text" name="name" value="{{ $recipe->name }} (Copia)" required class="w-full text-base px-4 py-3 rounded-xl bg-slate-50 dark:bg-stone-800 border-slate-200 dark:border-stone-700 text-slate-800 dark:text-stone-100 focus:border-orange-500 focus:ring-orange-500">
            </div>

            <!-- Acciones -->
            <div class="pt-4 flex justify-end gap-3">
                <button type="button" onclick="closeModal('duplicateRecipeModal-{{ $recipe->id }}')" class="px-4 py-2.5 text-sm font-medium text-slate-600 dark:text-stone-400 hover:text-slate-800 dark:hover:text-stone-200 transition-colors">
                    Cancelar
                </button>
                <button type="submit" class="px-6 py-2.5 bg-orange-600 hover:bg-orange-700 text-white font-medium text-sm rounded-xl shadow-lg shadow-orange-600/30 transition-all">
                    Sí, duplicar
                </button>
            </div>
        </form>
    </div>
</div>