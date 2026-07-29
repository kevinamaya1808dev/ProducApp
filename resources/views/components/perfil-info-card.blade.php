@props(['usuario' => null])

<div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
    <div class="bg-blue-600 h-24"></div>
    <div class="px-6 pb-6 relative">
        <div class="w-20 h-20 bg-blue-600 text-white border-4 border-white rounded-full flex items-center justify-center font-bold text-2xl shadow-sm absolute -top-10">
            {{ $usuario['iniciales'] ?? 'RL' }}
        </div>
        <div class="flex justify-end pt-3 mb-2">
            <span class="bg-emerald-50 text-emerald-700 border border-emerald-100 text-xs font-bold px-3 py-1 rounded-full">
                {{ $usuario['estado'] ?? 'Activo' }}
            </span>
        </div>
        <h2 class="text-xl font-bold text-slate-800">{{ $usuario['nombre'] ?? 'Roberto López' }}</h2>
        <p class="text-slate-500 text-sm mb-6">{{ $usuario['puesto'] ?? 'Operario Senior' }}</p>
        
        <ul class="space-y-3 text-sm">
            <li class="flex justify-between border-b border-slate-50 pb-2">
                <span class="text-slate-400 font-medium">ID</span>
                <span class="font-bold text-slate-800">{{ $usuario['id_operario'] ?? 'OP-001' }}</span>
            </li>
            <li class="flex justify-between border-b border-slate-50 pb-2">
                <span class="text-slate-400 font-medium">ESTACIÓN</span>
                <span class="font-medium text-slate-800">{{ $usuario['estacion'] ?? 'Estación 4' }}</span>
            </li>
            <li class="flex justify-between border-b border-slate-50 pb-2">
                <span class="text-slate-400 font-medium">TURNO</span>
                <span class="font-medium text-slate-800">{{ $usuario['turno'] ?? 'Matutino' }}</span>
            </li>
            <li class="flex justify-between border-b border-slate-50 pb-2">
                <span class="text-slate-400 font-medium">PLANTA</span>
                <span class="font-medium text-slate-800">{{ $usuario['planta'] ?? 'Monterrey' }}</span>
            </li>
            <li class="flex justify-between pt-1">
                <span class="text-slate-400 font-medium">ALTA DESDE</span>
                <span class="font-medium text-slate-800">{{ $usuario['alta_desde'] ?? 'Mar 2021' }}</span>
            </li>
        </ul>
    </div>
</div>