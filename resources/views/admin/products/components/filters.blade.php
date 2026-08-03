<form id="filter-form" method="GET" action="{{ route('products.index') }}" class="flex flex-col xl:flex-row gap-4 items-center justify-between">

    <!-- Búsqueda -->
    <div class="relative w-full xl:w-80 shrink-0">
        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
        </div>
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Buscar nombre o SKU..." class="w-full pl-10 pr-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm placeholder-slate-400 focus:ring-2 focus:ring-orange-500 focus:outline-none shadow-sm">
        @if(request('category_id'))
            <input type="hidden" name="category_id" value="{{ request('category_id') }}">
        @endif
    </div>

    <!-- Píldoras de Categorías -->
    <div class="flex-1 w-full flex overflow-x-auto gap-2 items-center hide-scrollbar pb-2 xl:pb-0">
        <a href="{{ route('products.index', ['search' => request('search')]) }}" class="px-4 py-2 rounded-lg text-sm font-semibold whitespace-nowrap transition-colors {{ !request('category_id') ? 'bg-orange-600 text-white shadow-sm' : 'bg-white border border-slate-200 text-slate-600 hover:bg-slate-50' }}">
            Todos
        </a>
        @foreach($categories as $category)
            <a href="{{ route('products.index', ['search' => request('search'), 'category_id' => $category->id]) }}" class="px-4 py-2 rounded-lg text-sm font-semibold whitespace-nowrap transition-colors {{ request('category_id') == $category->id ? 'bg-orange-600 text-white shadow-sm' : 'bg-white border border-slate-200 text-slate-600 hover:bg-slate-50' }}">
                {{ $category->name }}
            </a>
        @endforeach
    </div>

    <!-- Selector de Vista (Grid / Tabla) -->
    <div class="hidden sm:flex bg-white border border-slate-200 rounded-lg p-1 shrink-0 shadow-sm">
        <button type="button" id="btn-grid" onclick="toggleView('grid')" class="flex items-center gap-2 px-4 py-1.5 rounded-md text-sm font-semibold transition-colors bg-slate-900 text-white">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
            Grid
        </button>
        <button type="button" id="btn-table" onclick="toggleView('table')" class="flex items-center gap-2 px-4 py-1.5 rounded-md text-sm font-semibold transition-colors text-slate-500 hover:text-slate-900">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path></svg>
            Tabla
        </button>
    </div>
</form>