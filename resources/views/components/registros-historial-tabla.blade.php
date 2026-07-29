@props(['registros' => []])

<div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 h-full">
    <div class="mb-4">
        <h3 class="text-lg font-bold text-slate-800">Historial de Registros</h3>
        <p class="text-sm text-slate-500">Turno actual · {{ count($registros) > 0 ? count($registros) : 4 }} entradas</p>
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
                        <td class="py-3 px-4 text-slate-400 font-medium">{{ $reg['numero'] ?? '000' }}</td>
                        <td class="py-3 px-4 font-medium text-slate-700">{{ $reg['hora'] ?? '00:00' }}</td>
                        <td class="py-3 px-4 font-bold text-slate-800">+{{ $reg['cantidad'] ?? 0 }}</td>
                        <td class="py-3 px-4">
                            <span class="{{ $reg['tipo_clase'] ?? 'bg-slate-100 text-slate-600' }} px-2 py-1 rounded-md text-xs font-medium">{{ $reg['tipo'] ?? '+Lote' }}</span>
                        </td>
                        <td class="py-3 px-4 text-slate-400">{{ $reg['nota'] ?? '—' }}</td>
                    </tr>
                @empty
                    <tr class="border-b border-slate-50 hover:bg-slate-50">
                        <td class="py-3 px-4 text-slate-400 font-medium">004</td>
                        <td class="py-3 px-4 font-medium text-slate-700">10:50</td>
                        <td class="py-3 px-4 font-bold text-slate-800">+5</td>
                        <td class="py-3 px-4">
                            <span class="bg-slate-100 text-slate-600 px-2 py-1 rounded-md text-xs font-medium">+Lote</span>
                        </td>
                        <td class="py-3 px-4 text-slate-400">—</td>
                    </tr>
                    <tr class="border-b border-slate-50 hover:bg-slate-50">
                        <td class="py-3 px-4 text-slate-400 font-medium">003</td>
                        <td class="py-3 px-4 font-medium text-slate-700">10:35</td>
                        <td class="py-3 px-4 font-bold text-slate-800">+1</td>
                        <td class="py-3 px-4">
                            <span class="bg-blue-50 text-blue-600 px-2 py-1 rounded-md text-xs font-medium border border-blue-100">+1 Unidad</span>
                        </td>
                        <td class="py-3 px-4 text-slate-400">—</td>
                    </tr>
                    <tr class="border-b border-slate-50 hover:bg-slate-50">
                        <td class="py-3 px-4 text-slate-400 font-medium">002</td>
                        <td class="py-3 px-4 font-medium text-slate-700">10:12</td>
                        <td class="py-3 px-4 font-bold text-slate-800">+10</td>
                        <td class="py-3 px-4">
                            <span class="bg-slate-100 text-slate-600 px-2 py-1 rounded-md text-xs font-medium">+Lote</span>
                        </td>
                        <td class="py-3 px-4 text-slate-500">Lote validado con supervisor</td>
                    </tr>
                    <tr class="border-b border-slate-50 hover:bg-slate-50">
                        <td class="py-3 px-4 text-slate-400 font-medium">001</td>
                        <td class="py-3 px-4 font-medium text-slate-700">09:58</td>
                        <td class="py-3 px-4 font-bold text-slate-800">+5</td>
                        <td class="py-3 px-4">
                            <span class="bg-slate-100 text-slate-600 px-2 py-1 rounded-md text-xs font-medium">+Lote</span>
                        </td>
                        <td class="py-3 px-4 text-slate-400">—</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>