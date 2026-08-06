@props(['permisos' => []])

<div class="bg-white dark:bg-stone-900 rounded-2xl shadow-sm border border-amber-100 dark:border-stone-800 p-6">
    <h3 class="text-xs font-bold text-stone-400 dark:text-stone-500 uppercase tracking-wide mb-4">Permisos</h3>
    <div class="space-y-3">
        @forelse($permisos as $permiso)
            <div class="flex items-center space-x-3">
                <div class="w-6 h-6 rounded-full bg-emerald-50 dark:bg-emerald-950/50 border border-emerald-200 dark:border-emerald-900/50 flex items-center justify-center flex-shrink-0">
                    <svg class="w-3.5 h-3.5 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                </div>
                <h4 class="font-bold text-stone-800 dark:text-stone-100 text-sm">
                    {{ is_array($permiso) ? ($permiso['nombre'] ?? '') : $permiso }}
                </h4>
            </div>
        @empty
            <p class="text-sm text-stone-400 dark:text-stone-500">Aún no se han asignado permisos.</p>
        @endforelse
    </div>
</div>