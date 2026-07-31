@props(['habilidades' => []])

<div class="bg-white rounded-2xl shadow-sm border border-amber-100 p-6">
    <h3 class="text-xs font-bold text-stone-400 uppercase tracking-wide mb-4">Habilidades</h3>
    <div class="flex flex-wrap gap-2">
        @forelse($habilidades as $habilidad)
            <span class="bg-orange-50 text-orange-700 border border-orange-200/70 px-3 py-1.5 rounded-xl text-xs font-semibold">{{ $habilidad }}</span>
        @empty
            <p class="text-sm text-stone-400">Aún no se han registrado habilidades.</p>
        @endforelse
    </div>
</div>