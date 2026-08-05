@props(['registros' => []])

<div class="bg-white dark:bg-stone-900 rounded-2xl shadow-sm border border-amber-100 dark:border-stone-800 p-6 h-full">
    <div class="mb-4">
        <h3 class="text-lg font-bold text-stone-800 dark:text-stone-100">Historial de Registros</h3>
        <p class="text-sm text-stone-500 dark:text-stone-400">Turno actual · {{ count($registros) }} entradas</p>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="border-b border-stone-100 dark:border-stone-800 text-xs font-bold text-stone-400 dark:text-stone-500 uppercase tracking-wide">
                    <th class="py-3 px-4">#</th>
                    <th class="py-3 px-4">Hora</th>
                    <th class="py-3 px-4">Cantidad</th>
                    <th class="py-3 px-4">Tipo</th>
                    <th class="py-3 px-4">Nota</th>
                </tr>
            </thead>
            <tbody class="text-sm">
                @forelse($registros as $reg)
                    <tr class="border-b border-stone-50 dark:border-stone-800/60 hover:bg-stone-50/50 dark:hover:bg-stone-800/40 transition-colors">
                        <td class="py-3 px-4 text-stone-400 dark:text-stone-500 font-medium whitespace-nowrap">{{ $reg['numero'] }}</td>
                        <td class="py-3 px-4 font-medium text-stone-700 dark:text-stone-300 whitespace-nowrap">{{ $reg['hora'] }}</td>
                        <td class="py-3 px-4 font-bold text-stone-800 dark:text-stone-100 whitespace-nowrap">+{{ $reg['cantidad'] }}</td>
                        <td class="py-3 px-4 whitespace-nowrap">
                            <span class="{{ $reg['tipo_clase'] }} px-2 py-1 rounded-md text-xs font-medium inline-block">
                                {{ $reg['tipo'] }}
                            </span>
                        </td>
                        <td class="py-3 px-4 {{ $reg['nota'] !== '—' ? 'text-stone-700 dark:text-stone-300 font-medium' : 'text-stone-400 dark:text-stone-500' }}">
                            {{ $reg['nota'] }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="py-8 px-4 text-center text-stone-400 dark:text-stone-500 text-sm">
                            No hay registros de producción hoy.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>