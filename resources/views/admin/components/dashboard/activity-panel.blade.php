<div class="xl:col-span-1 bg-white dark:bg-stone-900 border border-slate-200/80 dark:border-stone-800 rounded-xl shadow-sm p-6 flex flex-col">
    <div class="mb-6">
        <h2 class="text-base font-bold text-slate-900 dark:text-stone-100">Actividad Reciente</h2>
        <p class="text-xs text-slate-500 dark:text-stone-400">Últimos eventos del sistema</p>
    </div>

    <div class="space-y-6 relative before:absolute before:inset-0 before:left-2 before:w-0.5 before:bg-slate-100 dark:before:bg-stone-800">
        @forelse($recentActivities ?? [] as $activity)
        <div class="relative pl-6">
            <span class="absolute left-0 top-1.5 w-4 h-4 rounded-full bg-white dark:bg-stone-900 border-2 border-orange-600 flex items-center justify-center">
                <span class="w-1.5 h-1.5 bg-orange-600 rounded-full"></span>
            </span>
            <p class="text-xs font-semibold text-slate-800 dark:text-stone-200 leading-snug">
                {{ $activity->description }}
            </p>
            <span class="text-[11px] text-slate-400 dark:text-stone-500 mt-0.5 block">
                {{ isset($activity->created_at) ? \Carbon\Carbon::parse($activity->created_at)->diffForHumans() : 'Reciente' }}
            </span>
        </div>
        @empty
        <div class="relative pl-6">
            <p class="text-xs text-slate-400 dark:text-stone-500">Sin actividad reciente registrada.</p>
        </div>
        @endforelse
    </div>
</div>