@props(['historial' => []])

<div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
    <div class="mb-4">
        <h3 class="text-lg font-bold text-slate-800">Historial de Órdenes</h3>
        <p class="text-sm text-slate-500">Últimas órdenes completadas</p>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="border-b border-slate-100 text-xs font-bold text-slate-400 uppercase tracking-wide">
                    <th class="py-3 px-2">Orden</th>
                    <th class="py-3 px-2">Producto</th>
                    <th class="py-3 px-2">Fecha</th>
                    <th class="py-3 px-2">Unidades</th>
                    <th class="py-3 px-2">Eficiencia</th>
                </tr>
            </thead>
            <tbody class="text-sm">
                @forelse($historial as $orden)
                    <tr class="border-b border-slate-50">
                        <td class="py-4 px-2 font-bold text-blue-600 text-xs">
                            <span class="bg-blue-50 px-2 py-1 rounded">{{ $orden['orden'] }}</span>
                        </td>
                        <td class="py-4 px-2 font-medium text-slate-700">{{ $orden['producto'] }}</td>
                        <td class="py-4 px-2 text-slate-500">{{ $orden['fecha'] }}</td>
                        <td class="py-4 px-2 font-bold text-slate-800">{{ $orden['unidades'] }}</td>
                        <td class="py-4 px-2">
                            @php
                                $colorBarra = $orden['eficiencia'] >= 95 ? 'bg-emerald-500' : ($orden['eficiencia'] >= 80 ? 'bg-blue-600' : 'bg-amber-400');
                                $colorTexto = $orden['eficiencia'] >= 95 ? 'text-emerald-600' : ($orden['eficiencia'] >= 80 ? 'text-blue-600' : 'text-amber-500');
                            @endphp
                            <div class="flex items-center space-x-2">
                                <div class="w-16 bg-slate-100 rounded-full h-1.5"><div class="{{ $colorBarra }} h-1.5 rounded-full" style="width: {{ min($orden['eficiencia'], 100) }}%"></div></div>
                                <span class="font-bold {{ $colorTexto }} text-xs">{{ $orden['eficiencia'] }}%</span>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="py-8 px-2 text-center text-slate-400 text-sm">
                            Aún no tienes órdenes completadas.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>