@props(['registros' => []])

<div class="bg-white rounded-2xl shadow-sm border border-amber-100 p-6 h-full">
    <div class="mb-4">
        <h3 class="text-lg font-bold text-stone-800">Historial de Registros</h3>
        <p class="text-sm text-stone-500">Turno actual · {{ count($registros) }} entradas</p>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="border-b border-stone-100 text-xs font-bold text-stone-400 uppercase tracking-wide">
                    <th class="py-3 px-4">#</th>
                    <th class="py-3 px-4">Hora</th>
                    <th class="py-3 px-4">Cantidad</th>
                    <th class="py-3 px-4">Tipo</th>
                    <th class="py-3 px-4">Nota</th>
                </tr>
            </thead>
            <tbody class="text-sm">
                @forelse($registros as $reg)
                    <tr class="border-b border-stone-50 hover:bg-stone-50/50 transition-colors">
                        <td class="py-3 px-4 text-stone-400 font-medium whitespace-nowrap">{{ $reg['numero'] }}</td>
                        <td class="py-3 px-4 font-medium text-stone-700 whitespace-nowrap">{{ $reg['hora'] }}</td>
                        <td class="py-3 px-4 font-bold text-stone-800 whitespace-nowrap">+{{ $reg['cantidad'] }}</td>
                        <td class="py-3 px-4 whitespace-nowrap">
                            <span class="{{ $reg['tipo_clase'] }} px-2 py-1 rounded-md text-xs font-medium inline-block">
                                {{ $reg['tipo'] }}
                            </span>
                        </td>
                        <td class="py-3 px-4 {{ $reg['nota'] !== '—' ? 'text-stone-700 font-medium' : 'text-stone-400' }}">
                            {{ $reg['nota'] }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="py-8 px-4 text-center text-stone-400 text-sm">
                            No hay registros de producción hoy.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>