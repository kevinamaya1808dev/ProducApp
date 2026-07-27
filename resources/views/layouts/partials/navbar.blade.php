<nav class="bg-white border-b border-gray-200 fixed z-30 w-full top-0 left-0 h-16 flex items-center justify-between px-4 shadow-sm">
    <div class="flex items-center gap-3">
        <a href="{{ url('/') }}" class="text-xl font-bold text-gray-800 flex items-center gap-2">
            <span class="bg-indigo-600 text-white px-2 py-1 rounded text-sm font-extrabold">PA</span>
            <span>{{ config('app.name', 'ProducApp') }}</span>
        </a>
    </div>

    <div class="flex items-center gap-4">
        @guest
            @if (Route::has('login'))
                <a href="{{ route('login') }}" class="text-sm font-medium text-gray-600 hover:text-indigo-600 transition-colors">
                    {{ __('Login') }}
                </a>
            @endif

            @if (Route::has('register'))
                <a href="{{ route('register') }}" class="text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 px-3 py-1.5 rounded-md transition-colors">
                    {{ __('Register') }}
                </a>
            @endif
        @else
            <div class="flex items-center gap-3">
                <span class="text-xs px-2.5 py-1 rounded-full font-semibold {{ Auth::user()->role === 'admin' ? 'bg-purple-100 text-purple-800 border border-purple-200' : 'bg-blue-100 text-blue-800 border border-blue-200' }}">
                    {{ ucfirst(Auth::user()->role) }}
                </span>
                <span class="text-sm font-medium text-gray-700">{{ Auth::user()->name }}</span>

                <form action="{{ route('logout') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="text-sm font-medium text-red-600 hover:text-red-800 transition-colors">
                        {{ __('Logout') }}
                    </button>
                </form>
            </div>
        @endguest
    </div>
</nav>