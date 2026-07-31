@props(['certificaciones' => []])

<div class="bg-white rounded-2xl shadow-sm border border-amber-100 p-6">
    <h3 class="text-xs font-bold text-stone-400 uppercase tracking-wide mb-4">Certificaciones</h3>
    <div class="space-y-4">
        @forelse($certificaciones as $cert)
            <div class="flex items-start space-x-3">
                <div class="w-6 h-6 rounded-full bg-emerald-50 border border-emerald-200 flex items-center justify-center flex-shrink-0 mt-0.5">
                    <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                </div>
                <div>
                    <h4 class="font-bold text-stone-800 text-sm">{{ $cert['nombre'] }}</h4>
                    <p class="text-xs text-stone-400 mt-0.5">{{ $cert['fecha'] }}</p>
                </div>
            </div>
        @empty
            <p class="text-sm text-stone-400">Aún no se han registrado certificaciones.</p>
        @endforelse
    </div>
</div>