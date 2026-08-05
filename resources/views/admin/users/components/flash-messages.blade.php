@if(session('success'))
    <div class="mb-4 bg-emerald-50 dark:bg-emerald-950/50 border border-emerald-200 dark:border-emerald-900/50 text-emerald-700 dark:text-emerald-400 text-sm font-medium px-4 py-3 rounded-lg shadow-sm">
        {{ session('success') }}
    </div>
@endif
@if(session('error'))
    <div class="mb-4 bg-red-50 dark:bg-red-950/50 border border-red-200 dark:border-red-900/50 text-red-700 dark:text-red-400 text-sm font-medium px-4 py-3 rounded-lg shadow-sm">
        {{ session('error') }}
    </div>
@endif