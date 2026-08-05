@props(['habilidades' => []])

<div class="bg-white dark:bg-stone-900 rounded-2xl shadow-sm border border-amber-100 dark:border-stone-800 p-6">
    <h3 class="text-xs font-bold text-stone-400 dark:text-stone-500 uppercase tracking-wide mb-4">Habilidades</h3>
    <div class="flex flex-wrap gap-2">
        @forelse($habilidades as $habilidad)
            <span class="bg-orange-50 dark:bg-orange-950/50 text-orange-700 dark:text-orange-400 border border-orange-200/70 dark:border-orange-900/50 px-3 py-1.5 rounded-xl text-xs font-semibold">{{ $habilidad }}</span>
        @empty
            <p class="text-sm text-stone-400 dark:text-stone-500">Aún no se han registrado habilidades.</p>
        @endforelse
    </div>
</div>