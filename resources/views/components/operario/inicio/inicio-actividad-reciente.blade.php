@props(['actividadesRecientes'])

<div class="bg-white rounded-2xl shadow-sm border border-amber-100 p-5">
    <h3 class="text-xs font-bold text-stone-400 uppercase tracking-wide mb-4">Actividad Reciente</h3>
    <div class="space-y-4">
        @forelse($actividadesRecientes ?? [] as $actividad)
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div class="w-3 h-3 rounded-full bg-orange-500"></div>
                    <span class="text-sm text-stone-800 font-medium">+{{ $actividad->cantidad }} piezas registradas</span>
                </div>
                <span class="text-xs text-stone-400">{{ $actividad->created_at->format('H:i') }}</span>
            </div>
        @empty
            <p class="text-xs text-stone-400 text-center py-2">No hay registros recientes hoy.</p>
        @endforelse
    </div>
</div>