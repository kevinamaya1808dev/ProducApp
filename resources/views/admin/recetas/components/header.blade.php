<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
    <div>
        <h1 class="text-2xl lg:text-3xl font-extrabold text-slate-900 tracking-tight">Recetas</h1>
        <p class="text-slate-500 text-sm mt-1">Gestión de recetas y fichas técnicas &middot; {{ $recipes->count() }} registros</p>
    </div>

    @if(Auth::user()->hasRole('admin') || Auth::user()->hasPermission('manage-recipes'))
    <div class="flex items-center gap-2">
        <a href="{{ route('component-types.index') }}" class="inline-flex items-center gap-2 bg-white border border-slate-200 hover:bg-slate-50 text-slate-600 font-semibold px-4 py-2.5 rounded-xl text-sm transition-all">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
            Tipos de Componente
        </a>
        <button type="button" onclick="openModal('createRecipeModal')" class="inline-flex items-center gap-2 bg-orange-600 hover:bg-orange-700 text-white font-semibold px-5 py-2.5 rounded-xl shadow-lg shadow-orange-600/20 transition-all text-sm">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Nueva Receta
        </button>
    </div>
    @endif
</div>