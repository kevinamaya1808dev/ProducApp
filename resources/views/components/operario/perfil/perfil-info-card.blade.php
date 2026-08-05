@props(['usuario' => null])

<div class="bg-white dark:bg-stone-900 rounded-2xl shadow-sm border border-amber-100 dark:border-stone-800 overflow-hidden">
    <div class="bg-gradient-to-r from-orange-600 to-amber-600 dark:from-orange-700 dark:to-amber-700 h-24"></div>
    <div class="px-6 pb-6 relative">
        <div class="w-20 h-20 bg-orange-600 text-white border-4 border-white dark:border-stone-900 rounded-full flex items-center justify-center font-bold text-2xl shadow-md absolute -top-10">
            {{ $usuario['iniciales'] ?? '—' }}
        </div>
        <div class="flex justify-end pt-3 mb-2">
            <span class="bg-emerald-50 dark:bg-emerald-950/50 text-emerald-700 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-900/50 text-[10px] font-bold px-3 py-1 rounded-full uppercase tracking-wider">
                {{ $usuario['estado'] ?? 'Sin definir' }}
            </span>
        </div>
        <h2 class="text-xl font-bold text-stone-800 dark:text-stone-100">{{ $usuario['nombre'] ?? 'Sin nombre' }}</h2>
        <p class="text-stone-500 dark:text-stone-400 text-sm mb-6">{{ $usuario['puesto'] ?? 'Operario' }}</p>
        
        <ul class="space-y-3 text-sm">
            <li class="flex justify-between border-b border-stone-100 dark:border-stone-800 pb-2">
                <span class="text-stone-400 dark:text-stone-500 font-medium">ID</span>
                <span class="font-bold text-stone-800 dark:text-stone-100">{{ $usuario['id_operario'] ?? '—' }}</span>
            </li>
            <li class="flex justify-between border-b border-stone-100 dark:border-stone-800 pb-2">
                <span class="text-stone-400 dark:text-stone-500 font-medium">ESTACIÓN</span>
                <span class="font-semibold text-stone-700 dark:text-stone-300">{{ $usuario['estacion'] ?? 'Sin asignar' }}</span>
            </li>
            <li class="flex justify-between border-b border-stone-100 dark:border-stone-800 pb-2">
                <span class="text-stone-400 dark:text-stone-500 font-medium">TURNO</span>
                <span class="font-semibold text-stone-700 dark:text-stone-300">{{ $usuario['turno'] ?? 'Sin definir' }}</span>
            </li>
            <li class="flex justify-between pt-1">
                <span class="text-stone-400 dark:text-stone-500 font-medium">ALTA DESDE</span>
                <span class="font-semibold text-stone-700 dark:text-stone-300">{{ $usuario['alta_desde'] ?? '—' }}</span>
            </li>
        </ul>
    </div>
</div>