<div class="p-6 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
    <div>
        <div class="flex items-center gap-3 flex-wrap">
            <h2 class="text-xl font-bold text-slate-900">{{ $recipe->name }}</h2>
            <span class="bg-orange-50 text-orange-700 text-xs font-mono font-semibold px-2.5 py-1 rounded-lg border border-orange-200/60">REC-00{{ $recipe->id }}</span>
        </div>
        <p class="text-xs text-slate-500 mt-1.5">Modificado: {{ $recipe->updated_at->translatedFormat('d M Y') }}</p>
    </div>
</div>

<div class="px-6 pt-5 flex flex-wrap items-center gap-2">
    @if(Auth::user()->hasRole('admin') || Auth::user()->hasPermission('manage-recipes'))
        <button type="button" onclick="openModal('addComponentModal')" class="inline-flex items-center gap-1.5 px-4 py-2 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 font-semibold text-sm rounded-xl border border-emerald-200 transition-all">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Componente
        </button>
        <button type="button" onclick="openModal('editRecipeModal-{{ $recipe->id }}')" class="px-4 py-2 bg-white border border-orange-200 hover:bg-orange-50 text-orange-600 font-semibold text-sm rounded-xl transition-all">
            Editar receta
        </button>
        <form action="{{ route('recipes.duplicate', $recipe->id) }}" method="POST" onsubmit="return confirm('¿Duplicar esta receta junto con sus componentes?');">
            @csrf
            <button type="submit" class="px-4 py-2 bg-white border border-slate-200 hover:bg-slate-50 text-slate-600 font-semibold text-sm rounded-xl transition-all">
                Duplicar
            </button>
        </form>
        <button type="button" onclick="openModal('deleteRecipeModal-{{ $recipe->id }}')" class="px-4 py-2 bg-white border border-red-200 hover:bg-red-50 text-red-600 font-semibold text-sm rounded-xl transition-all">
            Eliminar
        </button>
    @endif
</div>

@if($recipe->instructions)
    <div class="mx-6 mt-5 flex items-start gap-2 bg-amber-50 border border-amber-200/60 text-amber-800 text-xs px-4 py-3 rounded-xl">
        <svg class="w-4 h-4 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
        <span class="whitespace-pre-line">{{ $recipe->instructions }}</span>
    </div>
@endif