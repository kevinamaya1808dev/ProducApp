@props(['habilidades' => []])

<div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
    <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wide mb-4">Habilidades</h3>
    <div class="flex flex-wrap gap-2">
        @forelse($habilidades as $habilidad)
            <span class="bg-blue-50 text-blue-700 border border-blue-100 px-3 py-1.5 rounded-lg text-xs font-semibold">{{ $habilidad }}</span>
        @empty
            <span class="bg-blue-50 text-blue-700 border border-blue-100 px-3 py-1.5 rounded-lg text-xs font-semibold">Costura industrial</span>
            <span class="bg-blue-50 text-blue-700 border border-blue-100 px-3 py-1.5 rounded-lg text-xs font-semibold">Acabados</span>
            <span class="bg-blue-50 text-blue-700 border border-blue-100 px-3 py-1.5 rounded-lg text-xs font-semibold">Control de calidad</span>
            <span class="bg-blue-50 text-blue-700 border border-blue-100 px-3 py-1.5 rounded-lg text-xs font-semibold">Maquinaria overlock</span>
            <span class="bg-blue-50 text-blue-700 border border-blue-100 px-3 py-1.5 rounded-lg text-xs font-semibold">Corte básico</span>
        @endforelse
    </div>
</div>