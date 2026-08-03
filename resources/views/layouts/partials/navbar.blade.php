@auth
<nav class="bg-white/80 backdrop-blur-md border-b border-stone-200/80 fixed z-30 w-full top-0 left-0 h-16 flex items-center justify-between px-4 sm:px-6 shadow-sm transition-all">
    <div class="flex items-center gap-3">
        <!-- Botón menú móvil (Solo visible en celular/tablet) -->
        <button id="mobileMenuBtn" type="button" class="lg:hidden -ml-1 w-9 h-9 flex items-center justify-center text-stone-500 hover:text-orange-600 hover:bg-orange-50 rounded-lg transition-colors cursor-pointer">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
        </button>

        <!-- Logotipo y Nombre (Ahora va antes que el botón en escritorio) -->
        <a href="{{ url('/') }}" class="text-xl font-bold text-stone-800 flex items-center gap-2.5 group">
            <span class="w-8 h-8 rounded-lg bg-gradient-to-br from-orange-500 to-amber-600 text-white font-extrabold text-xs flex items-center justify-center shadow-md shadow-orange-500/20 group-hover:scale-105 transition-transform">
                PA
            </span>
            <span class="hidden sm:inline tracking-tight text-stone-900 font-bold text-lg">{{ config('app.name', 'ProducApp') }}</span>
        </a>

        <!-- Botón colapsar / expandir (Escritorio - Ahora va a la derecha del logo) -->
        <button id="sidebarCollapseBtn" type="button" class="hidden lg:flex w-9 h-9 items-center justify-center text-stone-500 hover:text-orange-600 hover:bg-orange-50 rounded-lg transition-colors cursor-pointer ml-1" title="Colapsar / Expandir Sidebar">
            <svg id="collapseIcon" class="w-5 h-5 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
        </button>
    </div>

    <div class="flex items-center gap-2 sm:gap-4">
        <!-- Badge de Rol -->
        <span class="hidden sm:inline-block text-xs px-3 py-1 rounded-full font-semibold transition-all {{ Auth::user()->role === 'admin' ? 'bg-orange-50 text-orange-700 border border-orange-200/80 shadow-xs' : 'bg-amber-50 text-amber-700 border border-amber-200/80 shadow-xs' }}">
            {{ ucfirst(Auth::user()->role) }}
        </span>

        <!-- Nombre de Usuario -->
        <span class="hidden md:inline text-sm font-medium text-stone-700">{{ Auth::user()->name }}</span>

        <div class="hidden sm:block h-4 w-[1px] bg-stone-200"></div>

        <!-- Botón Logout -->
        <form action="{{ route('logout') }}" method="POST" class="inline">
            @csrf
            <button type="submit" class="text-sm font-medium text-stone-500 hover:text-orange-600 hover:bg-orange-50/60 px-2 sm:px-2.5 py-1.5 rounded-lg transition-all cursor-pointer">
                {{ __('Logout') }}
            </button>
        </form>
    </div>
</nav>
@endauth