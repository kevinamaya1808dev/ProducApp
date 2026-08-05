@auth
<nav class="bg-white/80 dark:bg-stone-900/80 backdrop-blur-md border-b border-stone-200/80 dark:border-stone-800 fixed z-30 w-full top-0 left-0 h-16 flex items-center justify-between px-4 sm:px-6 shadow-sm transition-all">

    <!-- Línea de acento superior con gradiente -->
    <div class="absolute top-0 left-0 w-full h-[2px] bg-gradient-to-r from-orange-500 via-amber-500 to-orange-400 opacity-70"></div>

    <div class="flex items-center gap-3">
        <!-- Botón menú móvil -->
        <button id="mobileMenuBtn" type="button" class="lg:hidden -ml-1 w-9 h-9 flex items-center justify-center text-stone-500 dark:text-stone-400 hover:text-orange-600 dark:hover:text-orange-400 hover:bg-orange-50 dark:hover:bg-orange-950/50 rounded-lg transition-colors cursor-pointer active:scale-95">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
        </button>

        <!-- Logotipo y Nombre -->
        <a href="{{ url('/') }}" class="text-xl font-bold text-stone-800 dark:text-stone-100 flex items-center gap-2.5 group">
            <span class="w-8 h-8 rounded-lg bg-gradient-to-br from-orange-500 to-amber-600 dark:from-orange-600 dark:to-amber-700 text-white font-extrabold text-xs flex items-center justify-center shadow-md shadow-orange-500/20 group-hover:scale-105 group-hover:shadow-orange-500/40 transition-all duration-300">
                PA
            </span>
            <span class="hidden sm:inline tracking-tight text-stone-900 dark:text-stone-100 font-bold text-lg">
                {{ config('app.name', 'ProducApp') }}
            </span>
        </a>
    </div>

    <div class="flex items-center gap-2 sm:gap-4">
        <!-- Badge de Rol -->
        <span class="hidden sm:inline-flex items-center gap-1.5 text-xs px-3 py-1 rounded-full font-semibold transition-all {{ Auth::user()->role === 'admin' ? 'bg-orange-50 dark:bg-orange-950/50 text-orange-700 dark:text-orange-400 border border-orange-200/80 dark:border-orange-900/50 shadow-xs' : 'bg-amber-50 dark:bg-amber-950/50 text-amber-700 dark:text-amber-400 border border-amber-200/80 dark:border-amber-900/50 shadow-xs' }}">
            <span class="w-1.5 h-1.5 rounded-full {{ Auth::user()->role === 'admin' ? 'bg-orange-500' : 'bg-amber-500' }} animate-pulse"></span>
            {{ ucfirst(Auth::user()->role) }}
        </span>

        <!-- Botón Apariencia (Modo oscuro / claro) -->
        <button type="button" onclick="toggleDarkMode()" class="w-9 h-9 flex items-center justify-center text-stone-500 dark:text-stone-400 hover:text-orange-600 dark:hover:text-orange-400 hover:bg-orange-50 dark:hover:bg-orange-950/50 rounded-lg transition-colors cursor-pointer active:scale-95" title="Cambiar modo oscuro/claro">
            <svg class="w-5 h-5 hidden dark:block text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>
            </svg>
            <svg class="w-5 h-5 block dark:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
            </svg>
        </button>

        <div class="hidden sm:block h-6 w-[1px] bg-stone-200 dark:bg-stone-800"></div>

        <!-- Menú de usuario con dropdown (vanilla JS) -->
        <div class="relative" id="userMenuWrapper">
            <button type="button" id="userMenuBtn" class="flex items-center gap-2.5 pl-1 pr-2 py-1 rounded-lg hover:bg-orange-50/60 dark:hover:bg-orange-950/40 transition-colors cursor-pointer">
                <!-- Avatar con iniciales -->
                <span class="w-8 h-8 rounded-full bg-gradient-to-br from-orange-400 to-amber-500 dark:from-orange-500 dark:to-amber-600 text-white text-xs font-bold flex items-center justify-center shadow-sm ring-2 ring-white dark:ring-stone-900">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </span>
                <span class="hidden md:flex flex-col items-start leading-tight">
                    <span class="text-sm font-semibold text-stone-700 dark:text-stone-200">{{ Auth::user()->name }}</span>
                </span>
                <svg id="userMenuChevron" class="hidden md:block w-4 h-4 text-stone-400 dark:text-stone-500 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
            </button>

            <!-- Dropdown -->
            <div id="userMenuDropdown" class="hidden absolute right-0 mt-2 w-52 rounded-xl bg-white dark:bg-stone-900 border border-stone-200/80 dark:border-stone-800 shadow-lg shadow-stone-900/5 dark:shadow-black/30 py-1.5 overflow-hidden">
                <div class="px-3.5 py-2.5 border-b border-stone-100 dark:border-stone-800">
                    <p class="text-sm font-semibold text-stone-800 dark:text-stone-100 truncate">{{ Auth::user()->name }}</p>
                    <p class="text-xs text-stone-500 dark:text-stone-400 truncate">{{ Auth::user()->email }}</p>
                </div>

                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-2 text-left text-sm font-medium text-stone-600 dark:text-stone-300 hover:text-orange-600 dark:hover:text-orange-400 hover:bg-orange-50/60 dark:hover:bg-orange-950/50 px-3.5 py-2 transition-colors cursor-pointer">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                       Cerrar sesión
                    </button>
                </form>
            </div>
        </div>
    </div>
</nav>

@push('scripts')
<script>
    (function () {
        const btn = document.getElementById('userMenuBtn');
        const dropdown = document.getElementById('userMenuDropdown');
        const chevron = document.getElementById('userMenuChevron');
        if (!btn || !dropdown) return;

        btn.addEventListener('click', function (e) {
            e.stopPropagation();
            dropdown.classList.toggle('hidden');
            chevron.classList.toggle('rotate-180');
        });

        document.addEventListener('click', function (e) {
            if (!dropdown.contains(e.target) && !btn.contains(e.target)) {
                dropdown.classList.add('hidden');
                chevron.classList.remove('rotate-180');
            }
        });
    })();
</script>
@endpush
@endauth