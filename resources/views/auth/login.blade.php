@extends('layouts.app')

@section('content')
<div class="min-h-screen -m-4 sm:-m-6 flex flex-col lg:flex-row">

<!-- Botón de cambio de tema (visible sin sesión) -->
<button type="button" onclick="toggleDarkMode()" class="group fixed top-4 right-4 sm:top-6 sm:right-6 z-20 w-11 h-11 flex items-center justify-center bg-stone-900/5 dark:bg-stone-900/60 backdrop-blur-xl border border-stone-900/10 dark:border-stone-700/80 rounded-full shadow-lg shadow-black/10 dark:shadow-black/30 hover:border-orange-400/60 dark:hover:border-orange-500/50 hover:shadow-orange-500/20 transition-all duration-300 cursor-pointer active:scale-90" title="Cambiar modo oscuro/claro">
    <!-- Halo de brillo al hover -->
    <span class="absolute inset-0 rounded-full bg-orange-400/0 group-hover:bg-orange-400/10 dark:group-hover:bg-orange-500/10 transition-colors duration-300"></span>

    <!-- Ícono luna (modo oscuro activo) -->
    <svg class="relative w-[18px] h-[18px] hidden dark:block text-amber-400 group-hover:rotate-[25deg] group-hover:scale-110 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>
    </svg>

    <!-- Ícono sol (modo claro activo) -->
    <svg class="relative w-[18px] h-[18px] block dark:hidden text-orange-600 group-hover:-rotate-[25deg] group-hover:scale-110 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
    </svg>
</button>

    <!-- Panel Izquierdo: Información y Marca -->
    <div class="lg:w-1/2 bg-stone-950 text-white p-8 sm:p-10 lg:p-12 flex flex-col justify-between relative overflow-hidden">

        <!-- Acentos de luz ambientales -->
        <div class="pointer-events-none absolute inset-0 overflow-hidden">
            <div class="absolute -top-32 -right-32 w-80 h-80 bg-orange-600/20 rounded-full blur-3xl"></div>
            <div class="absolute -bottom-40 -left-20 w-72 h-72 bg-amber-600/10 rounded-full blur-3xl"></div>
        </div>

        <div class="relative z-10">
            <!-- Logo ProducApp -->
            <div class="flex items-center gap-3 mb-10 lg:mb-12">
                <span class="bg-gradient-to-br from-orange-500 to-amber-600 text-white font-extrabold w-9 h-9 rounded-lg flex items-center justify-center text-base shadow-md shadow-orange-500/20">P</span>
                <span class="text-xl font-bold tracking-tight">{{ config('app.name', 'ProducApp') }}</span>
            </div>

            <!-- Cabecera de Módulo -->
            <div class="max-w-md mt-2 lg:mt-6">
                <div class="w-12 h-12 bg-orange-950/40 border border-orange-500/30 rounded-xl flex items-center justify-center mb-6 text-orange-400">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5m0 0h4m-4 0V11m0 0h4m-4 0H7"></path>
                    </svg>
                </div>

                <h1 class="text-3xl lg:text-4xl font-extrabold text-white leading-tight mb-4">
                    Plataforma de Gestión Industrial
                </h1>

                <p class="text-stone-400 text-sm lg:text-base mb-8 leading-relaxed">
                    Control total de tu línea de producción: órdenes, operarios, recetas y métricas en tiempo real.
                </p>

                <!-- Características de la Plataforma -->
                <ul class="space-y-3.5 text-sm font-medium text-stone-300">
                    <li class="flex items-center gap-3">
                        <div class="w-5 h-5 rounded-full bg-orange-500/15 text-orange-400 flex items-center justify-center flex-shrink-0">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                        </div>
                        <span>Trazabilidad de lotes en tiempo real</span>
                    </li>
                    <li class="flex items-center gap-3">
                        <div class="w-5 h-5 rounded-full bg-orange-500/15 text-orange-400 flex items-center justify-center flex-shrink-0">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                        </div>
                        <span>Control de eficiencia por estación</span>
                    </li>
                    <li class="flex items-center gap-3">
                        <div class="w-5 h-5 rounded-full bg-orange-500/15 text-orange-400 flex items-center justify-center flex-shrink-0">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                        </div>
                        <span>Gestión de recetas y componentes</span>
                    </li>
                    <li class="flex items-center gap-3">
                        <div class="w-5 h-5 rounded-full bg-orange-500/15 text-orange-400 flex items-center justify-center flex-shrink-0">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                        </div>
                        <span>Reportes exportables por turno</span>
                    </li>
                </ul>
            </div>
        </div>

        <div class="relative z-10 mt-10 lg:mt-12 text-xs text-stone-500">
            &copy; {{ date('Y') }} {{ config('app.name', 'ProducApp') }} &middot; Planta Monterrey &middot; v2.4.1
        </div>
    </div>

    <!-- Panel Derecho: Formulario de Autenticación -->
    <div class="lg:w-1/2 bg-stone-50 dark:bg-stone-900 p-6 sm:p-10 lg:p-12 flex flex-col justify-center items-center transition-colors">
        <div class="w-full max-w-md">
            <h2 class="text-2xl lg:text-3xl font-bold text-stone-900 dark:text-stone-100 mb-1.5">Bienvenido de vuelta</h2>
            <p class="text-sm text-stone-500 dark:text-stone-400 mb-6">Ingresa con tu cuenta de administrador o supervisor.</p>

            <!-- Formulario Principal -->
            <form method="POST" action="{{ route('login') }}" class="space-y-4">
                @csrf

                <div>
                    <label for="email" class="block text-xs font-semibold text-stone-700 dark:text-stone-300 mb-1.5">Correo electrónico</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-stone-400 dark:text-stone-500">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                        </div>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus
                            placeholder="correo@empresa.mx"
                            class="w-full pl-9 pr-3 py-2.5 bg-white dark:bg-stone-800 border border-stone-200 dark:border-stone-700 rounded-lg text-sm text-stone-800 dark:text-stone-100 placeholder-stone-400 dark:placeholder-stone-500 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all @error('email') border-red-500 dark:border-red-500 @enderror">
                    </div>
                    @error('email')
                        <p class="mt-1 text-xs text-red-600 dark:text-red-400 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="block text-xs font-semibold text-stone-700 dark:text-stone-300 mb-1.5">Contraseña</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-stone-400 dark:text-stone-500">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                        </div>
                        <input id="password" type="password" name="password" required autocomplete="current-password"
                            placeholder="••••••••"
                            class="w-full pl-9 pr-3 py-2.5 bg-white dark:bg-stone-800 border border-stone-200 dark:border-stone-700 rounded-lg text-sm text-stone-800 dark:text-stone-100 placeholder-stone-400 dark:placeholder-stone-500 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all @error('password') border-red-500 dark:border-red-500 @enderror">
                    </div>
                    @error('password')
                        <p class="mt-1 text-xs text-red-600 dark:text-red-400 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-between pt-1">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}
                            class="w-4 h-4 text-orange-600 rounded border-stone-300 dark:border-stone-600 dark:bg-stone-800 focus:ring-orange-500">
                        <span class="text-xs text-stone-600 dark:text-stone-400">Mantener sesión iniciada</span>
                    </label>
                </div>

                <button type="submit" class="w-full py-2.5 px-4 bg-gradient-to-r from-orange-600 to-amber-600 hover:from-orange-700 hover:to-amber-700 text-white font-semibold text-sm rounded-lg shadow-sm shadow-orange-500/20 hover:shadow-md hover:shadow-orange-500/30 transition-all duration-200 cursor-pointer">
                    Iniciar sesión
                </button>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    if (typeof toggleDarkMode !== 'function') {
        function toggleDarkMode() {
            if (document.documentElement.classList.contains('dark')) {
                document.documentElement.classList.remove('dark');
                localStorage.setItem('theme', 'light');
            } else {
                document.documentElement.classList.add('dark');
                localStorage.setItem('theme', 'dark');
            }
        }
    }
</script>
@endpush