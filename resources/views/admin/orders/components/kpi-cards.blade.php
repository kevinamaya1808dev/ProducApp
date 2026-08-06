<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    @php
        $totalOrders = $orders->total() ?? count($orders);
        $inProgress = $orders->where('status', 'in_progress')->count();
        $completed = $orders->where('status', 'completed')->count();
        $pending = $orders->where('status', 'pending')->count();
    @endphp
    <div class="bg-white dark:bg-stone-900 p-5 rounded-2xl border border-slate-200 dark:border-stone-800 shadow-sm">
        <p class="text-xs font-semibold text-slate-400 dark:text-stone-500 uppercase tracking-wider">Total Órdenes</p>
        <h3 class="text-2xl font-black text-slate-900 dark:text-stone-100 mt-1">{{ $totalOrders }}</h3>
    </div>
    <div class="bg-white dark:bg-stone-900 p-5 rounded-2xl border border-slate-200 dark:border-stone-800 shadow-sm">
        <p class="text-xs font-semibold text-orange-500 uppercase tracking-wider">En Progreso</p>
        <h3 class="text-2xl font-black text-slate-900 dark:text-stone-100 mt-1">{{ $inProgress }}</h3>
    </div>
    <div class="bg-white dark:bg-stone-900 p-5 rounded-2xl border border-slate-200 dark:border-stone-800 shadow-sm">
        <p class="text-xs font-semibold text-emerald-500 uppercase tracking-wider">Completadas</p>
        <h3 class="text-2xl font-black text-slate-900 dark:text-stone-100 mt-1">{{ $completed }}</h3>
    </div>
    <div class="bg-white dark:bg-stone-900 p-5 rounded-2xl border border-slate-200 dark:border-stone-800 shadow-sm">
        <p class="text-xs font-semibold text-amber-500 uppercase tracking-wider">Pendientes</p>
        <h3 class="text-2xl font-black text-slate-900 dark:text-stone-100 mt-1">{{ $pending }}</h3>
    </div>
</div>