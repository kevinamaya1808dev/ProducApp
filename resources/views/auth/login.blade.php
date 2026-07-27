@extends('layouts.app')

@section('content')
<div class="min-h-[calc(100vh-7rem)] -m-6 flex flex-col lg:flex-row">
    <!-- Panel Izquierdo: Información y Marca -->
    <div class="lg:w-1/2 bg-slate-950 text-white p-8 lg:p-12 flex flex-col justify-between relative overflow-hidden">
        <div>
            <!-- Logo ProducApp -->
            <div class="flex items-center gap-3 mb-12">
                <span class="bg-blue-600 text-white font-bold w-9 h-9 rounded-lg flex items-center justify-center text-base shadow-md">P</span>
                <span class="text-xl font-bold tracking-tight">ProducApp</span>
            </div>

            <!-- Cabecera de Módulo -->
            <div class="max-w-md mt-6">
                <div class="w-12 h-12 bg-blue-900/30 border border-blue-500/30 rounded-xl flex items-center justify-center mb-6 text-blue-400">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5m0 0h4m-4 0V11m0 0h4m-4 0H7"></path>
                    </svg>
                </div>

                <h1 class="text-3xl lg:text-4xl font-extrabold text-white leading-tight mb-4">
                    Plataforma de Gestión Industrial
                </h1>
                
                <p class="text-slate-400 text-sm lg:text-base mb-8 leading-relaxed">
                    Control total de tu línea de producción: órdenes, operarios, recetas y métricas en tiempo real.
                </p>

                <!-- Características de la Plataforma -->
                <ul class="space-y-3.5 text-sm font-medium text-slate-300">
                    <li class="flex items-center gap-3">
                        <div class="w-5 h-5 rounded-full bg-blue-600/20 text-blue-400 flex items-center justify-center flex-shrink-0">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                        </div>
                        <span>Trazabilidad de lotes en tiempo real</span>
                    </li>
                    <li class="flex items-center gap-3">
                        <div class="w-5 h-5 rounded-full bg-blue-600/20 text-blue-400 flex items-center justify-center flex-shrink-0">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                        </div>
                        <span>Control de eficiencia por estación</span>
                    </li>
                    <li class="flex items-center gap-3">
                        <div class="w-5 h-5 rounded-full bg-blue-600/20 text-blue-400 flex items-center justify-center flex-shrink-0">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                        </div>
                        <span>Gestión de recetas y componentes</span>
                    </li>
                    <li class="flex items-center gap-3">
                        <div class="w-5 h-5 rounded-full bg-blue-600/20 text-blue-400 flex items-center justify-center flex-shrink-0">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                        </div>
                        <span>Reportes exportables por turno</span>
                    </li>
                </ul>
            </div>
        </div>

        <div class="mt-12 text-xs text-slate-500">
            &copy; 2026 ProducApp &middot; Planta Monterrey &middot; v2.4.1
        </div>
    </div>

    <!-- Panel Derecho: Formulario de Autenticación -->
    <div class="lg:w-1/2 bg-slate-50/50 p-6 lg:p-12 flex flex-col justify-center items-center">
        <div class="w-full max-w-md">
            <h2 class="text-2xl lg:text-3xl font-bold text-slate-900 mb-1.5">Bienvenido de vuelta</h2>
            <p class="text-sm text-slate-500 mb-6">Ingresa con tu cuenta de administrador o supervisor.</p>

            <!-- Formulario Principal -->
            <form method="POST" action="{{ route('login') }}" class="space-y-4">
                @csrf

                <div>
                    <label for="email" class="block text-xs font-semibold text-slate-700 mb-1.5">Correo electrónico</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                        </div>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus
                            placeholder="correo@empresa.mx"
                            class="w-full pl-9 pr-3 py-2.5 bg-white border border-slate-200 rounded-lg text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all @error('email') border-red-500 @enderror">
                    </div>
                    @error('email')
                        <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="block text-xs font-semibold text-slate-700 mb-1.5">Contraseña</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                        </div>
                        <input id="password" type="password" name="password" required autocomplete="current-password"
                            placeholder="••••••••"
                            class="w-full pl-9 pr-3 py-2.5 bg-white border border-slate-200 rounded-lg text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all @error('password') border-red-500 @enderror">
                    </div>
                    @error('password')
                        <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-between pt-1">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}
                            class="w-4 h-4 text-blue-600 rounded border-slate-300 focus:ring-blue-500">
                        <span class="text-xs text-slate-600">Mantener sesión iniciada</span>
                    </label>
                </div>

                <button type="submit" class="w-full py-2.5 px-4 bg-blue-600 hover:bg-blue-700 text-white font-semibold text-sm rounded-lg shadow-sm hover:shadow transition-all duration-200">
                    Iniciar sesión
                </button>
            </form>

            <div class="mt-8 text-center">
                <p class="text-xs text-slate-500">
                    ¿Problemas para acceder? <a href="#" class="text-blue-600 hover:underline font-medium">Contactar soporte</a>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection