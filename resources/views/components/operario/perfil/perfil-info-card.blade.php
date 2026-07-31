@props(['usuario' => null])

<div class="bg-white rounded-2xl shadow-sm border border-amber-100 overflow-hidden">
    <div class="bg-gradient-to-r from-orange-600 to-amber-600 h-24"></div>
    <div class="px-6 pb-6 relative">
        <div class="w-20 h-20 bg-orange-600 text-white border-4 border-white rounded-full flex items-center justify-center font-bold text-2xl shadow-md absolute -top-10">
            {{ $usuario['iniciales'] ?? '—' }}
        </div>
        <div class="flex justify-end pt-3 mb-2">
            <span class="bg-emerald-50 text-emerald-700 border border-emerald-200 text-[10px] font-bold px-3 py-1 rounded-full uppercase tracking-wider">
                {{ $usuario['estado'] ?? 'Sin definir' }}
            </span>
        </div>
        <h2 class="text-xl font-bold text-stone-800">{{ $usuario['nombre'] ?? 'Sin nombre' }}</h2>
        <p class="text-stone-500 text-sm mb-6">{{ $usuario['puesto'] ?? 'Operario' }}</p>
        
        <ul class="space-y-3 text-sm">
            <li class="flex justify-between border-b border-stone-100 pb-2">
                <span class="text-stone-400 font-medium">ID</span>
                <span class="font-bold text-stone-800">{{ $usuario['id_operario'] ?? '—' }}</span>
            </li>
            <li class="flex justify-between border-b border-stone-100 pb-2">
                <span class="text-stone-400 font-medium">ESTACIÓN</span>
                <span class="font-semibold text-stone-700">{{ $usuario['estacion'] ?? 'Sin asignar' }}</span>
            </li>
            <li class="flex justify-between border-b border-stone-100 pb-2">
                <span class="text-stone-400 font-medium">TURNO</span>
                <span class="font-semibold text-stone-700">{{ $usuario['turno'] ?? 'Sin definir' }}</span>
            </li>
            <li class="flex justify-between pt-1">
                <span class="text-stone-400 font-medium">ALTA DESDE</span>
                <span class="font-semibold text-stone-700">{{ $usuario['alta_desde'] ?? '—' }}</span>
            </li>
        </ul>
    </div>
</div>