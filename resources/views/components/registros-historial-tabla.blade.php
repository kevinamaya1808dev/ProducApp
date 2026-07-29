@props(['registros' => []])

<div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 h-full">
    <div class="mb-4">
        <h3 class="text-lg font-bold text-slate-800">Historial de Registros</h3>
        <p class="text-sm text-slate-500">Turno actual · {{ count($registros) }} entradas</p>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="border-b border-slate-100 text-xs font-bold text-slate-400 uppercase tracking-wide">
                    <th class="py-3 px-4">#</th>
                    <th class="py-3 px-4">Hora</th>
                    <th class="py-3 px-4">Cantidad</th>
                    <th class="py-3 px-4">Tipo</th>
                    <th class="py-3 px-4">Nota</th>
                </tr>
            </thead>
            <tbody class="text-sm">
                @forelse($registros as $reg)
                    <tr class="border-b border-slate-50 hover:bg-slate-50">
                        <td class="py-3 px-4 text-slate-400 font-medium">{{ $reg['numero'] }}</td>
                        <td class="py-3 px-4 font-medium text-slate-700">{{ $reg['hora'] }}</td>
                        <td class="py-3 px-4 font-bold text-slate-800">+{{ $reg['cantidad'] }}</td>
                        <td class="py-3 px-4">
                            <span class="{{ $reg['tipo_clase'] }} px-2 py-1 rounded-md text-xs font-medium">{{ $reg['tipo'] }}</span>
                        </td>
                        <td class="py-3 px-4 text-slate-400">{{ $reg['nota'] }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="py-8 px-4 text-center text-slate-400 text-sm">
                            No hay registros de producción hoy.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>