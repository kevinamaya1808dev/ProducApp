<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
    <div>
        <h1 class="text-2xl lg:text-3xl font-extrabold text-slate-900 tracking-tight">Categorías</h1>
        <p class="text-slate-500 text-sm mt-1">Gestión de categorías y clasificaciones &middot; {{ $categories->total() ?? $categories->count() }} registros</p>
    </div>

    <div>
        <button type="button" onclick="openModal('createCategoryModal')" class="inline-flex items-center gap-2 bg-orange-600 hover:bg-orange-700 text-white font-semibold px-5 py-2.5 rounded-xl shadow-lg shadow-orange-600/20 transition-all text-sm">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Nueva Categoría
        </button>
    </div>
</div>