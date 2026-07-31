@auth
<nav class="bg-white/80 backdrop-blur-md border-b border-stone-200/80 fixed z-30 w-full top-0 left-0 h-16 flex items-center justify-between px-6 shadow-sm transition-all">
    <div class="flex items-center gap-3">
        <a href="{{ url('/') }}" class="text-xl font-bold text-stone-800 flex items-center gap-2.5 group">
            <span class="w-8 h-8 rounded-lg bg-gradient-to-br from-orange-500 to-amber-600 text-white font-extrabold text-xs flex items-center justify-center shadow-md shadow-orange-500/20 group-hover:scale-105 transition-transform">
                PA
            </span>
            <span class="tracking-tight text-stone-900 font-bold text-lg">{{ config('app.name', 'ProducApp') }}</span>
        </a>
    </div>

    <div class="flex items-center gap-4">
        <!-- Badge de Rol -->
        <span class="text-xs px-3 py-1 rounded-full font-semibold transition-all {{ Auth::user()->role === 'admin' ? 'bg-orange-50 text-orange-700 border border-orange-200/80 shadow-xs' : 'bg-amber-50 text-amber-700 border border-amber-200/80 shadow-xs' }}">
            {{ ucfirst(Auth::user()->role) }}
        </span>

        <!-- Nombre de Usuario -->
        <span class="text-sm font-medium text-stone-700">{{ Auth::user()->name }}</span>

        <div class="h-4 w-[1px] bg-stone-200"></div>

        <!-- Botón Logout -->
        <form action="{{ route('logout') }}" method="POST" class="inline">
            @csrf
            <button type="submit" class="text-sm font-medium text-stone-500 hover:text-orange-600 hover:bg-orange-50/60 px-2.5 py-1.5 rounded-lg transition-all cursor-pointer">
                {{ __('Logout') }}
            </button>
        </form>
    </div>
</nav>
@endauth