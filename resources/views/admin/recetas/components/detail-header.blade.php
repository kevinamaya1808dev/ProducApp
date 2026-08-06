<div class="p-6 border-b border-slate-100 dark:border-stone-800 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
    <div>
        <div class="flex items-center gap-3 flex-wrap">
            <h2 class="text-xl font-bold text-slate-900 dark:text-stone-100">{{ $recipe->name }}</h2>
            <span class="bg-orange-50 dark:bg-orange-950/50 text-orange-700 dark:text-orange-400 text-xs font-mono font-semibold px-2.5 py-1 rounded-lg border border-orange-200/60 dark:border-orange-900/50">REC-00{{ $recipe->id }}</span>
        </div>
        <p class="text-xs text-slate-500 dark:text-stone-400 mt-1.5">Modificado: {{ $recipe->updated_at->translatedFormat('d M Y') }}</p>
    </div>
</div>

<div class="px-6 pt-5 flex flex-wrap items-center gap-2">
    @can('manage-recipes')
        <button type="button" onclick="openModal('addComponentModal')" class="inline-flex items-center gap-1.5 px-4 py-2 bg-emerald-50 dark:bg-emerald-950/40 hover:bg-emerald-100 dark:hover:bg-emerald-900/50 text-emerald-700 dark:text-emerald-400 font-semibold text-sm rounded-xl border border-emerald-200 dark:border-emerald-900/50 transition-all">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Componente
        </button>
        
        <button type="button" onclick="openModal('editRecipeModal-{{ $recipe->id }}')" class="px-4 py-2 bg-white dark:bg-stone-900 border border-orange-200 dark:border-orange-900/50 hover:bg-orange-50 dark:hover:bg-orange-950/40 text-orange-600 dark:text-orange-400 font-semibold text-sm rounded-xl transition-all">
            Editar receta
        </button>

        <!-- Botón actualizado para abrir el modal de duplicar -->
        <button type="button" onclick="openModal('duplicateRecipeModal-{{ $recipe->id }}')" class="px-4 py-2 bg-white dark:bg-stone-900 border border-slate-200 dark:border-stone-800 hover:bg-slate-50 dark:hover:bg-stone-800 text-slate-600 dark:text-stone-300 font-semibold text-sm rounded-xl transition-all">
            Duplicar
        </button>

        <button type="button" onclick="openModal('deleteRecipeModal-{{ $recipe->id }}')" class="px-4 py-2 bg-white dark:bg-stone-900 border border-red-200 dark:border-red-900/50 hover:bg-red-50 dark:hover:bg-red-950/40 text-red-600 dark:text-red-400 font-semibold text-sm rounded-xl transition-all">
            Eliminar
        </button>
    @endcan
</div>

@if($recipe->instructions)
    <div class="mx-6 mt-5 flex items-start gap-2 bg-amber-50 dark:bg-amber-950/40 border border-amber-200/60 dark:border-amber-900/50 text-amber-800 dark:text-amber-300 text-xs px-4 py-3 rounded-xl">
        <svg class="w-4 h-4 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
        <span class="whitespace-pre-line">{{ $recipe->instructions }}</span>
    </div>
@endif