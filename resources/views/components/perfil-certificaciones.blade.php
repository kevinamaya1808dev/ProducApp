@props(['certificaciones' => []])

<div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
    <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wide mb-4">Certificaciones</h3>
    <div class="space-y-4">
        @forelse($certificaciones as $cert)
            <div class="flex items-start space-x-3">
                <div class="w-6 h-6 rounded-full bg-emerald-100 flex items-center justify-center flex-shrink-0 mt-0.5">
                    <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                </div>
                <div>
                    <h4 class="font-bold text-slate-800 text-sm">{{ $cert['nombre'] }}</h4>
                    <p class="text-xs text-slate-400">{{ $cert['fecha'] }}</p>
                </div>
            </div>
        @empty
            <p class="text-sm text-slate-400">Aún no se han registrado certificaciones.</p>
        @endforelse
    </div>
</div>