<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'ProducApp') }}</title>

    <!-- Scripts and Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 font-sans antialiased text-gray-900">
    <div id="app" class="min-h-screen flex flex-col">
        @include('layouts.partials.navbar')

        <div class="flex pt-16 flex-1">
            @include('layouts.partials.sidebar')

            <main class="flex-1 p-6 transition-all duration-300 {{ Auth::check() ? 'ml-64' : 'ml-0' }}">
                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>