<div class="p-6 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
    <div>
        <div class="flex items-center gap-3 flex-wrap">
            <h2 class="text-xl font-bold text-slate-900">{{ $category->name }}</h2>
            <span class="bg-orange-50 text-orange-700 text-xs font-mono font-semibold px-2.5 py-1 rounded-lg border border-orange-200/60">
                Slug: {{ $category->slug }}
            </span>
        </div>
        <p class="text-xs text-slate-500 mt-1.5">Creada el {{ $category->created_at->format('d M Y') }} &middot; Última actualización: {{ $category->updated_at->format('d M Y') }}</p>
    </div>

    <div class="flex items-center gap-2">
        <button type="button" onclick="openModal('editCategoryModal-{{ $category->id }}')" class="px-4 py-2 bg-orange-600 hover:bg-orange-700 text-white font-medium text-sm rounded-xl transition-all shadow-md shadow-orange-600/25">
            Editar Categoría
        </button>
        <button type="button" onclick="openModal('deleteCategoryModal-{{ $category->id }}')" class="px-4 py-2 bg-white border border-red-200 hover:bg-red-50 text-red-600 font-medium text-sm rounded-xl transition-all shadow-sm">
            Eliminar
        </button>
    </div>
</div>