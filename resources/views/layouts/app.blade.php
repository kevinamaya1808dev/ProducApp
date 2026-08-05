<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'ProducApp') }}</title>

    <!-- Script inline de prevención de parpadeo (FOUC) para Modo Oscuro -->
    <script>
        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>

    <!-- Scripts and Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-stone-50 dark:bg-stone-950 text-stone-800 dark:text-stone-100 font-sans antialiased min-h-screen selection:bg-orange-500 selection:text-white relative overflow-x-hidden transition-colors duration-300">

    <!-- Ambient Glow -->
<div class="pointer-events-none fixed inset-0 -z-10 overflow-hidden">

    <!-- Base: gradiente vertical sutil (ambos temas) -->
    <div class="absolute inset-0 bg-gradient-to-b from-amber-50/50 dark:from-stone-950 via-transparent to-orange-50/20 dark:to-stone-900/20"></div>

    <!-- Spotlight superior centrado -->
    <div class="absolute -top-56 left-1/2 -translate-x-1/2 w-[40rem] h-[28rem] bg-orange-200/40 dark:bg-orange-900/20 rounded-full blur-3xl animate-[pulse_11s_ease-in-out_infinite]"></div>

    <!-- Acento inferior, descentrado para balancear -->
    <div class="absolute -bottom-32 right-[15%] w-80 h-80 bg-amber-100/45 dark:bg-amber-950/15 rounded-full blur-3xl animate-[pulse_13s_ease-in-out_infinite] [animation-delay:-5s]"></div>

</div>

    <!-- Overlay para sidebar móvil -->
    @auth
        <div id="sidebarOverlay" class="fixed inset-0 bg-stone-900/50 z-10 hidden lg:hidden"></div>
    @endauth

    <div id="app" class="min-h-screen flex flex-col relative z-10">
        @auth
            @include('layouts.partials.navbar')
        @endauth

        <!-- Contenedor principal con pt-16 fijo si hay autenticación -->
        <div class="flex flex-1 @auth pt-16 @endauth">
            @auth
                @include('layouts.partials.sidebar')
            @endauth

           <main id="mainContent" class="flex-1 min-w-0 p-4 sm:p-6 transition-all duration-300 @auth lg:ml-64 @else ml-0 @endauth">
                @yield('content')
           </main>
        </div>
    </div>

@include('layouts.partials.toast-container')

    @auth
    <script>
        (function () {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('mainContent');
            const overlay = document.getElementById('sidebarOverlay');
            const collapseBtn = document.getElementById('sidebarCollapseBtn');
            const collapseIcon = document.getElementById('collapseIcon');
            const mobileMenuBtn = document.getElementById('mobileMenuBtn');
            const sidebarCloseBtn = document.getElementById('sidebarCloseBtn');

            if (!sidebar || !mainContent) return;

            // ---- Colapsar / expandir (escritorio) ----
            function applyCollapsed(collapsed) {
                sidebar.classList.toggle('lg:w-20', collapsed);
                sidebar.classList.toggle('lg:w-64', !collapsed);
                mainContent.classList.toggle('lg:ml-20', collapsed);
                mainContent.classList.toggle('lg:ml-64', !collapsed);

                document.querySelectorAll('.sidebar-label').forEach(function (el) {
                    el.classList.toggle('lg:hidden', collapsed);
                });
                document.querySelectorAll('.sidebar-nav-item').forEach(function (el) {
                    el.classList.toggle('lg:justify-center', collapsed);
                });

                if (collapseIcon) {
                    if (collapsed) {
                        collapseIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7"></path>';
                    } else {
                        collapseIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7"></path>';
                    }
                }
            }

            const savedCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
            applyCollapsed(savedCollapsed);

            if (collapseBtn) {
                collapseBtn.addEventListener('click', function () {
                    const isCollapsed = sidebar.classList.contains('lg:w-20');
                    const next = !isCollapsed;
                    applyCollapsed(next);
                    localStorage.setItem('sidebarCollapsed', next);
                });
            }

            // ---- Abrir / cerrar (móvil) ----
            function openMobileSidebar() {
                sidebar.classList.remove('-translate-x-full');
                sidebar.classList.add('translate-x-0');
                if (overlay) overlay.classList.remove('hidden');
            }

            function closeMobileSidebar() {
                sidebar.classList.add('-translate-x-full');
                sidebar.classList.remove('translate-x-0');
                if (overlay) overlay.classList.add('hidden');
            }

            if (mobileMenuBtn) {
                mobileMenuBtn.addEventListener('click', openMobileSidebar);
            }
            if (sidebarCloseBtn) {
                sidebarCloseBtn.addEventListener('click', closeMobileSidebar);
            }
            if (overlay) {
                overlay.addEventListener('click', closeMobileSidebar);
            }

            window.addEventListener('resize', function () {
                if (window.innerWidth >= 1024) {
                    closeMobileSidebar();
                }
            });
        })();
    </script>
    @endauth
    @stack('scripts')
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</body>
</html>