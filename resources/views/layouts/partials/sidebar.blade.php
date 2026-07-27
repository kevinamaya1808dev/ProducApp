@auth
<aside class="w-64 bg-slate-900 text-slate-300 h-[calc(100vh-4rem)] fixed top-16 left-0 z-20 flex flex-col justify-between border-r border-slate-800">
    <div class="py-4 px-3">
        <div class="mb-4 px-3 text-xs font-semibold text-slate-400 uppercase tracking-wider">
            Menú de Navegación
        </div>

        <nav class="space-y-1">
            @if(Auth::user()->role === 'admin')
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-3 py-2 rounded-md text-sm font-medium transition-colors {{ request()->routeIs('admin.dashboard') ? 'bg-indigo-600 text-white' : 'hover:bg-slate-800 text-slate-300' }}">
                    <span>Dashboard Admin</span>
                </a>

                <a href="{{ route('categories.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-md text-sm font-medium transition-colors {{ request()->routeIs('categories.*') ? 'bg-indigo-600 text-white' : 'hover:bg-slate-800 text-slate-300' }}">
                    <span>Categorías</span>
                </a>

                <a href="{{ route('products.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-md text-sm font-medium transition-colors {{ request()->routeIs('products.*') ? 'bg-indigo-600 text-white' : 'hover:bg-slate-800 text-slate-300' }}">
                    <span>Productos</span>
                </a>

                <a href="{{ route('orders.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-md text-sm font-medium transition-colors {{ request()->routeIs('orders.*') ? 'bg-indigo-600 text-white' : 'hover:bg-slate-800 text-slate-300' }}">
                    <span>Órdenes de Producción</span>
                </a>
            @elseif(Auth::user()->role === 'operario')
                <a href="{{ route('operario.dashboard') }}" class="flex items-center gap-3 px-3 py-2 rounded-md text-sm font-medium transition-colors {{ request()->routeIs('operario.dashboard') ? 'bg-indigo-600 text-white' : 'hover:bg-slate-800 text-slate-300' }}">
                    <span>Dashboard Operario</span>
                </a>
            @endif
        </nav>
    </div>

    <div class="p-4 border-t border-slate-800 text-xs text-slate-500">
        ProducApp &copy; {{ date('Y') }}
    </div>
</aside>
@endauth