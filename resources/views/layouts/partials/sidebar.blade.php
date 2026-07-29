@auth
<aside class="w-64 bg-[#0f172a] text-slate-300 h-screen fixed top-0 left-0 z-20 flex flex-col justify-between border-r border-slate-800/50 shadow-2xl">
    
    <div class="flex-1 overflow-y-auto custom-scrollbar">
        <!-- Cabecera / Logo -->
        <div class="px-6 py-6 mb-2">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 bg-blue-600 rounded-xl flex items-center justify-center font-extrabold text-white text-xl shadow-lg shadow-blue-900/20">
                    P
                </div>
                <div>
                    <h1 class="text-white font-bold text-lg leading-tight tracking-wide">ProducApp</h1>
                    <p class="text-slate-500 text-[10px] font-bold tracking-widest mt-0.5">ADMIN PANEL</p>
                </div>
            </div>
        </div>

        <nav class="space-y-1.5 px-3 pb-6">
            
            {{-- Sección Exclusiva de Administrador --}}
            @if(Auth::user()->hasRole('admin'))
                <div class="px-3 pt-4 pb-2 text-[11px] font-bold text-slate-500 uppercase tracking-widest">
                    Principal
                </div>

                <!-- Dashboard -->
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition-all relative {{ request()->routeIs('admin.dashboard') ? 'bg-blue-600 text-white shadow-md shadow-blue-900/20' : 'text-slate-400 hover:text-slate-200 hover:bg-slate-800/50' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                    <span>Dashboard</span>
                    @if(request()->routeIs('admin.dashboard')) <span class="absolute right-4 w-1.5 h-1.5 rounded-full bg-white opacity-90"></span> @endif
                </a>
            @endif

            {{-- Módulo de Productos --}}
            @if(Auth::user()->hasRole('admin') || Auth::user()->hasPermission('access-products'))
                <a href="{{ route('products.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition-all relative {{ request()->routeIs('products.*') ? 'bg-blue-600 text-white shadow-md shadow-blue-900/20' : 'text-slate-400 hover:text-slate-200 hover:bg-slate-800/50' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                    <span>Productos</span>
                    @if(request()->routeIs('products.*')) <span class="absolute right-4 w-1.5 h-1.5 rounded-full bg-white opacity-90"></span> @endif
                </a>
            @endif

            {{-- Módulo de Recetas (Corregido a la ruta de recipes.index) --}}
            @if(Auth::user()->hasRole('admin') || Auth::user()->hasPermission('gestionar-recetas'))
                <a href="{{ route('recipes.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition-all relative {{ request()->routeIs('recipes.*') ? 'bg-blue-600 text-white shadow-md shadow-blue-900/20' : 'text-slate-400 hover:text-slate-200 hover:bg-slate-800/50' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    <span>Recetas</span>
                    @if(request()->routeIs('recipes.*')) <span class="absolute right-4 w-1.5 h-1.5 rounded-full bg-white opacity-90"></span> @endif
                </a>
            @endif

            {{-- Módulo de Categorías (Añadido independientemente) --}}
            @if(Auth::user()->hasRole('admin') || Auth::user()->hasPermission('gestionar-categorias'))
                <a href="{{ route('categories.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition-all relative {{ request()->routeIs('categories.*') ? 'bg-blue-600 text-white shadow-md shadow-blue-900/20' : 'text-slate-400 hover:text-slate-200 hover:bg-slate-800/50' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                    <span>Categorías</span>
                    @if(request()->routeIs('categories.*')) <span class="absolute right-4 w-1.5 h-1.5 rounded-full bg-white opacity-90"></span> @endif
                </a>
            @endif

            {{-- Módulo de Órdenes --}}
            @if(Auth::user()->hasRole('admin') || Auth::user()->hasPermission('gestionar-ordenes'))
                <a href="{{ route('orders.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition-all relative {{ request()->routeIs('orders.*') ? 'bg-blue-600 text-white shadow-md shadow-blue-900/20' : 'text-slate-400 hover:text-slate-200 hover:bg-slate-800/50' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9.5a2.5 2.5 0 00-2.5-2.5H15"></path></svg>
                    <span>Órdenes</span>
                    @if(request()->routeIs('orders.*')) <span class="absolute right-4 w-1.5 h-1.5 rounded-full bg-white opacity-90"></span> @endif
                </a>
            @endif

            {{-- Módulo de Operarios --}}
            @if(Auth::user()->hasRole('admin') || Auth::user()->hasPermission('gestionar-usuarios'))
                <a href="{{ route('users.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition-all relative {{ request()->routeIs('users.*') ? 'bg-blue-600 text-white shadow-md shadow-blue-900/20' : 'text-slate-400 hover:text-slate-200 hover:bg-slate-800/50' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    <span>Operarios</span>
                    @if(request()->routeIs('users.*')) <span class="absolute right-4 w-1.5 h-1.5 rounded-full bg-white opacity-90"></span> @endif
                </a>
            @endif

            {{-- Sección de Sistema --}}
            @if(Auth::user()->hasRole('admin') || Auth::user()->hasPermission('gestionar-configuracion'))
                <div class="px-3 pt-6 pb-2 text-[11px] font-bold text-slate-500 uppercase tracking-widest">
                    Sistema
                </div>
                <a href="#" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition-all text-slate-400 hover:text-slate-200 hover:bg-slate-800/50">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    <span>Configuración</span>
                </a>
            @endif

            {{-- Sección Exclusiva de Operario --}}
            @if(Auth::user()->hasRole('operario'))
                <div class="px-3 pt-4 pb-2 text-[11px] font-bold text-slate-500 uppercase tracking-widest">
                    Mi Espacio
                </div>

                <a href="{{ route('operario.inicio') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition-all relative {{ request()->routeIs('operario.inicio') ? 'bg-blue-600 text-white shadow-md shadow-blue-900/20' : 'text-slate-400 hover:text-slate-200 hover:bg-slate-800/50' }}">
                    <span>Inicio Operario</span>
                    @if(request()->routeIs('operario.inicio')) <span class="absolute right-4 w-1.5 h-1.5 rounded-full bg-white opacity-90"></span> @endif
                </a>

                <a href="{{ route('operario.tareas') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition-all relative {{ request()->routeIs('operario.tareas') ? 'bg-blue-600 text-white shadow-md shadow-blue-900/20' : 'text-slate-400 hover:text-slate-200 hover:bg-slate-800/50' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                    <span>Mis Tareas</span>
                    @if(request()->routeIs('operario.tareas')) <span class="absolute right-4 w-1.5 h-1.5 rounded-full bg-white opacity-90"></span> @endif
                </a>

                <a href="{{ route('operario.registro') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition-all relative {{ request()->routeIs('operario.registro') ? 'bg-blue-600 text-white shadow-md shadow-blue-900/20' : 'text-slate-400 hover:text-slate-200 hover:bg-slate-800/50' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                    <span>Registro</span>
                    @if(request()->routeIs('operario.registro')) <span class="absolute right-4 w-1.5 h-1.5 rounded-full bg-white opacity-90"></span> @endif
                </a>

                <a href="{{ route('operario.incidencias') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition-all relative {{ request()->routeIs('operario.incidencias') ? 'bg-blue-600 text-white shadow-md shadow-blue-900/20' : 'text-slate-400 hover:text-slate-200 hover:bg-slate-800/50' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    <span>Incidencias</span>
                    @if(request()->routeIs('operario.incidencias')) <span class="absolute right-4 w-1.5 h-1.5 rounded-full bg-white opacity-90"></span> @endif
                </a>

                <a href="{{ route('operario.perfil') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition-all relative {{ request()->routeIs('operario.perfil') ? 'bg-blue-600 text-white shadow-md shadow-blue-900/20' : 'text-slate-400 hover:text-slate-200 hover:bg-slate-800/50' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    <span>Mi Perfil</span>
                    @if(request()->routeIs('operario.perfil')) <span class="absolute right-4 w-1.5 h-1.5 rounded-full bg-white opacity-90"></span> @endif
                </a>
            @endif
        </nav>
    </div>

    <!-- Perfil del usuario / Footer -->
    <div class="p-4 bg-[#0a101d] border-t border-slate-800/80">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-blue-600 flex items-center justify-center text-white font-bold text-sm shadow-md">
                    {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                </div>
                <div class="flex flex-col">
                    <span class="text-sm font-bold text-white leading-tight">{{ Auth::user()->name }}</span>
                    <span class="text-[11px] text-slate-400 mt-0.5">
                        {{ Auth::user()->roles->pluck('name')->implode(', ') ?: 'Sin rol' }} &middot; Planta Central
                    </span>
                </div>
            </div>
            
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="p-2 text-slate-500 hover:text-white hover:bg-slate-800 rounded-lg transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                    </svg>
                </button>
            </form>
        </div>
    </div>
</aside>
@endauth