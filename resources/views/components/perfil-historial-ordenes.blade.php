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
                <tr class="border-b border-slate-50">
                    <td class="py-4 px-2 font-bold text-blue-600 text-xs">
                        <span class="bg-blue-50 px-2 py-1 rounded">ORD-2024-0089</span>
                    </td>
                    <td class="py-4 px-2 font-medium text-slate-700">Blusa Lino Temporada 26</td>
                    <td class="py-4 px-2 text-slate-500">20 jul 2026</td>
                    <td class="py-4 px-2 font-bold text-slate-800">100</td>
                    <td class="py-4 px-2">
                        <div class="flex items-center space-x-2">
                            <div class="w-16 bg-slate-100 rounded-full h-1.5"><div class="bg-emerald-500 h-1.5 rounded-full" style="width: 96%"></div></div>
                            <span class="font-bold text-emerald-600 text-xs">96%</span>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td class="py-4 px-2 font-bold text-blue-600 text-xs">
                        <span class="bg-blue-50 px-2 py-1 rounded">ORD-2024-0085</span>
                    </td>
                    <td class="py-4 px-2 font-medium text-slate-700">Short Deportivo Dry-Fit</td>
                    <td class="py-4 px-2 text-slate-500">18 jul 2026</td>
                    <td class="py-4 px-2 font-bold text-slate-800">80</td>
                    <td class="py-4 px-2">
                        <div class="flex items-center space-x-2">
                            <div class="w-16 bg-slate-100 rounded-full h-1.5"><div class="bg-blue-600 h-1.5 rounded-full" style="width: 92%"></div></div>
                            <span class="font-bold text-blue-600 text-xs">92%</span>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>