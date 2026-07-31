<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'ProducApp') }}</title>

    <!-- Scripts and Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-stone-50 text-stone-800 font-sans antialiased min-h-screen selection:bg-orange-500 selection:text-white relative overflow-x-hidden">

    <!-- Ambient Glow / Tone Naranja Difuminado (Atmósfera cálida y profesional) -->
    <div class="pointer-events-none fixed inset-0 -z-10 overflow-hidden">
        <!-- Destello naranja en esquina superior derecha -->
        <div class="absolute -top-40 -right-40 w-96 h-96 bg-orange-200/40 rounded-full blur-3xl"></div>
        <!-- Destello cálido en esquina inferior izquierda -->
        <div class="absolute -bottom-40 -left-40 w-96 h-96 bg-amber-100/50 rounded-full blur-3xl"></div>
        <!-- Capa de degradado sutil general -->
        <div class="absolute inset-0 bg-gradient-to-br from-amber-50/60 via-transparent to-orange-50/30"></div>
    </div>

    <div id="app" class="min-h-screen flex flex-col relative z-10">
        @auth
            @include('layouts.partials.navbar')
        @endauth

        <div class="flex {{ Auth::check() ? 'pt-16' : '' }} flex-1">
            @include('layouts.partials.sidebar')

            <main class="flex-1 p-6 transition-all duration-300 {{ Auth::check() ? 'ml-64' : 'ml-0' }}">
                @yield('content')
            </main>
        </div>
    </div>

    @stack('scripts')
</body>
</html>