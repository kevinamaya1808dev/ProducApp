@auth
<aside id="sidebar" class="w-64 lg:w-64 bg-stone-900 dark:bg-stone-900 text-stone-300 dark:text-stone-300 h-[calc(100vh-4rem)] fixed top-16 left-0 z-20 flex flex-col justify-between border-r border-stone-800/80 dark:border-stone-800/80 shadow-2xl transform -translate-x-full lg:translate-x-0 transition-all duration-300">

    <!-- Botón cerrar (solo móvil) -->
    <button id="sidebarCloseBtn" type="button" class="lg:hidden absolute right-3 top-4 w-8 h-8 flex items-center justify-center text-stone-400 hover:text-white dark:hover:text-white hover:bg-stone-800 rounded-lg transition-colors cursor-pointer z-30">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
    </button>
    
    <div class="flex-1 overflow-y-auto overflow-x-hidden custom-scrollbar pt-3">
        <nav class="space-y-1.5 px-3 pb-6">
            
            {{-- SECCIÓN DE VISTAS / MÓDULOS ADMINISTRATIVOS --}}
            @canany(['view-admin-dashboard', 'view-products', 'view-recipes', 'view-categories', 'view-orders', 'manage-orders', 'view-users'])
                
                <div class="sidebar-label px-3 pt-2 pb-2 text-[11px] font-bold text-stone-500 uppercase tracking-widest whitespace-nowrap overflow-hidden">
                    {{ Auth::user()->hasRole('admin') ? 'Principal / Admin' : 'Módulos Autorizados' }}
                </div>

                {{-- Dashboard --}}
                @can('view-admin-dashboard')
                    <a href="{{ route('admin.dashboard') }}" class="sidebar-nav-item flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('admin.dashboard') ? 'bg-orange-600 text-white shadow-md shadow-orange-950/30' : 'text-stone-400 hover:text-stone-200 dark:hover:text-stone-200 hover:bg-stone-800/60' }}">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                        <span class="sidebar-label whitespace-nowrap overflow-hidden">Dashboard</span>
                    </a>
                @endcan

                {{-- Productos --}}
                @can('view-products')
                    <a href="{{ route('products.index') }}" class="sidebar-nav-item flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('products.*') ? 'bg-orange-600 text-white shadow-md shadow-orange-950/30' : 'text-stone-400 hover:text-stone-200 dark:hover:text-stone-200 hover:bg-stone-800/60' }}">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                        <span class="sidebar-label whitespace-nowrap overflow-hidden">Productos</span>
                    </a>
                @endcan

                {{-- Recetas --}}
                @can('view-recipes')
                    <a href="{{ route('recipes.index') }}" class="sidebar-nav-item flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('recipes.*') ? 'bg-orange-600 text-white shadow-md shadow-orange-950/30' : 'text-stone-400 hover:text-stone-200 dark:hover:text-stone-200 hover:bg-stone-800/60' }}">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        <span class="sidebar-label whitespace-nowrap overflow-hidden">Recetas</span>
                    </a>
                @endcan

                {{-- Categorías --}}
                @can('view-categories')
                    <a href="{{ route('categories.index') }}" class="sidebar-nav-item flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('categories.*') ? 'bg-orange-600 text-white shadow-md shadow-orange-950/30' : 'text-stone-400 hover:text-stone-200 dark:hover:text-stone-200 hover:bg-stone-800/60' }}">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                        <span class="sidebar-label whitespace-nowrap overflow-hidden">Categorías</span>
                    </a>
                @endcan

                {{-- Órdenes --}}
                @can('view-orders')
                    <a href="{{ route('orders.index') }}" class="sidebar-nav-item flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('orders.*') ? 'bg-orange-600 text-white shadow-md shadow-orange-950/30' : 'text-stone-400 hover:text-stone-200 dark:hover:text-stone-200 hover:bg-stone-800/60' }}">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9.5a2.5 2.5 0 00-2.5-2.5H15"></path></svg>
                        <span class="sidebar-label whitespace-nowrap overflow-hidden">Órdenes</span>
                    </a>
                @endcan

                {{-- Gestión de Incidencias --}}
                @can('manage-orders')
                    <a href="{{ route('admin.incidences.index') }}" class="sidebar-nav-item flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('admin.incidences.*') ? 'bg-orange-600 text-white shadow-md shadow-orange-950/30' : 'text-stone-400 hover:text-stone-200 dark:hover:text-stone-200 hover:bg-stone-800/60' }}">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                        <span class="sidebar-label whitespace-nowrap overflow-hidden">Gestión Incidencias</span>
                    </a>
                @endcan

                {{-- Operarios / Usuarios --}}
                @can('view-users')
                    <a href="{{ route('admin.users.index') }}" class="sidebar-nav-item flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('admin.users.*') ? 'bg-orange-600 text-white shadow-md shadow-orange-950/30' : 'text-stone-400 hover:text-stone-200 dark:hover:text-stone-200 hover:bg-stone-800/60' }}">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        <span class="sidebar-label whitespace-nowrap overflow-hidden">Operarios</span>
                    </a>
                @endcan
            @endcanany

            {{-- SECCIÓN EXCLUSIVA DE OPERARIO (Validada únicamente con access-operario) --}}
            @can('access-operario')
                <div class="sidebar-label px-3 pt-4 pb-2 text-[11px] font-bold text-stone-500 uppercase tracking-widest whitespace-nowrap overflow-hidden">
                    Mi Espacio
                </div>

                <a href="{{ route('operario.inicio') }}" class="sidebar-nav-item flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('operario.inicio') ? 'bg-orange-600 text-white shadow-md shadow-orange-950/30' : 'text-stone-400 hover:text-stone-200 dark:hover:text-stone-200 hover:bg-stone-800/60' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                    <span class="sidebar-label whitespace-nowrap overflow-hidden">Inicio Operario</span>
                </a>

                <a href="{{ route('operario.tareas') }}" class="sidebar-nav-item flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('operario.tareas') ? 'bg-orange-600 text-white shadow-md shadow-orange-950/30' : 'text-stone-400 hover:text-stone-200 dark:hover:text-stone-200 hover:bg-stone-800/60' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                    <span class="sidebar-label whitespace-nowrap overflow-hidden">Mis Tareas</span>
                </a>

                <a href="{{ route('operario.registro') }}" class="sidebar-nav-item flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('operario.registro') ? 'bg-orange-600 text-white shadow-md shadow-orange-950/30' : 'text-stone-400 hover:text-stone-200 dark:hover:text-stone-200 hover:bg-stone-800/60' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                    <span class="sidebar-label whitespace-nowrap overflow-hidden">Registro</span>
                </a>

                <a href="{{ route('operario.incidencias') }}" class="sidebar-nav-item flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('operario.incidencias') ? 'bg-orange-600 text-white shadow-md shadow-orange-950/30' : 'text-stone-400 hover:text-stone-200 dark:hover:text-stone-200 hover:bg-stone-800/60' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    <span class="sidebar-label whitespace-nowrap overflow-hidden">Incidencias</span>
                </a>

                <a href="{{ route('operario.perfil') }}" class="sidebar-nav-item flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('operario.perfil') ? 'bg-orange-600 text-white shadow-md shadow-orange-950/30' : 'text-stone-400 hover:text-stone-200 dark:hover:text-stone-200 hover:bg-stone-800/60' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    <span class="sidebar-label whitespace-nowrap overflow-hidden">Mi Perfil</span>
                </a>
            @endcan
        </nav>
    </div>

    <!-- Pie de página -->
    <div class="p-3 bg-stone-950/95 dark:bg-stone-950 border-t border-stone-800/80 dark:border-stone-800 flex flex-col gap-2">
        <!-- Botón para alternar Modo Oscuro / Claro -->
        <button type="button" onclick="toggleDarkMode()" class="sidebar-nav-item w-full flex items-center gap-3 px-3 py-2 text-stone-400 hover:text-white dark:hover:text-white hover:bg-stone-800/60 rounded-xl text-sm font-medium transition-all cursor-pointer" title="Cambiar modo oscuro/claro">
            <svg class="w-5 h-5 shrink-0 hidden dark:block text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>
            </svg>
            <svg class="w-5 h-5 shrink-0 block dark:hidden text-stone-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
            </svg>
            <span class="sidebar-label whitespace-nowrap overflow-hidden">Apariencia</span>
        </button>

        <form action="{{ route('logout') }}" method="POST" class="w-full">
            @csrf
            <button type="submit" class="sidebar-nav-item w-full flex items-center gap-3 px-3 py-2 text-red-400 hover:text-white hover:bg-red-600/20 rounded-xl text-sm font-medium transition-all group cursor-pointer" title="Cerrar sesión">
                <svg class="w-5 h-5 shrink-0 text-red-400 group-hover:text-red-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                </svg>
                <span class="sidebar-label whitespace-nowrap overflow-hidden">Cerrar sesión</span>
            </button>
        </form>

        <div class="flex items-center gap-3 px-2 py-1 min-w-0">
            <div class="w-9 h-9 shrink-0 rounded-full bg-gradient-to-br from-orange-500 to-amber-600 flex items-center justify-center text-white font-bold text-xs shadow-md">
                {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
            </div>
            <div class="sidebar-label flex flex-col min-w-0 overflow-hidden whitespace-nowrap">
                <span class="text-xs font-bold text-white leading-tight truncate">{{ Auth::user()->name }}</span>
                <span class="text-[10px] text-stone-400 mt-0.5 truncate">
                    {{ Auth::user()->roles->pluck('name')->implode(', ') ?: 'Sin rol' }}
                </span>
            </div>
        </div>
    </div>
</aside>

<script>
    function toggleDarkMode() {
        if (document.documentElement.classList.contains('dark')) {
            document.documentElement.classList.remove('dark');
            localStorage.setItem('theme', 'light');
        } else {
            document.documentElement.classList.add('dark');
            localStorage.setItem('theme', 'dark');
        }
    }
</script>
@endauth