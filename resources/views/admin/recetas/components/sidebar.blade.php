<div class="lg:col-span-4 space-y-4">
    <form action="{{ route('recipes.index') }}" method="GET" class="relative">
        <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-slate-400">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
        </span>
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Buscar receta..." class="w-full pl-10 pr-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-orange-500/20 focus:border-orange-600 transition-all shadow-sm">
    </form>

    <div class="space-y-3 max-h-[calc(100vh-250px)] overflow-y-auto pr-1">
        @forelse($recipes as $recipe)
            @include('admin.recetas.components.recipe-card', ['recipe' => $recipe])
        @empty
            <div class="bg-white border border-slate-200 rounded-2xl p-6 text-center text-slate-500 text-sm shadow-sm">No se encontraron recetas registradas.</div>
        @endforelse
    </div>
</div>