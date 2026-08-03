<div class="xl:col-span-3 bg-white border border-slate-200/80 rounded-xl shadow-sm p-6">
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 mb-6">
        <div>
            <h2 class="text-base font-bold text-slate-900">Órdenes de Producción</h2>
            <p class="text-xs text-slate-500">{{ isset($orders) ? $orders->total() : 0 }} órdenes registradas</p>
        </div>

        <div class="flex flex-wrap items-center gap-3">
            <form method="GET" action="{{ route('admin.dashboard') }}" class="relative w-full sm:w-64">
                @if(request('status'))
                    <input type="hidden" name="status" value="{{ request('status') }}">
                @endif
                @if(request('date'))
                    <input type="hidden" name="date" value="{{ request('date') }}">
                @endif
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Buscar por número, producto..." class="w-full pl-9 pr-3 py-1.5 bg-slate-50 border border-slate-200 rounded-lg text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-orange-500">
            </form>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="border-b border-slate-100 text-[11px] font-bold text-slate-400 uppercase tracking-wider">
                    <th class="pb-3 pr-4">ID de Orden</th>
                    <th class="pb-3 px-4">Producto</th>
                    <th class="pb-3 px-4">Progreso</th>
                    <th class="pb-3 px-4">Fecha Límite</th>
                    <th class="pb-3 px-4">Operario</th>
                    <th class="pb-3 pl-4 text-right">Estado</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 text-xs text-slate-700">
                @forelse($orders ?? [] as $order)
                @php $progress = $order->porcentaje_avance ?? $order->progress ?? 0; @endphp
                <tr class="hover:bg-slate-50/50 transition-colors">
                    <td class="py-3.5 pr-4 font-semibold text-orange-600 whitespace-nowrap">
                        {{ $order->order_number ?? $order->order_code ?? 'ORD-'.$order->id }}
                    </td>
                    <td class="py-3.5 px-4">
                        <p class="font-bold text-slate-800">{{ $order->product->name ?? 'Producto N/D' }}</p>
                        <p class="text-[11px] text-slate-400">{{ $order->product->category->name ?? 'General' }}</p>
                    </td>
                    <td class="py-3.5 px-4 w-40">
                        <div class="flex items-center gap-2">
                            <div class="flex-1 bg-slate-100 rounded-full h-2 overflow-hidden">
                                <div class="bg-orange-500 h-2 rounded-full transition-all duration-300" style="width: {{ $progress }}%"></div>
                            </div>
                            <span class="text-[11px] font-semibold text-slate-500 w-8 text-right">{{ $progress }}%</span>
                        </div>
                    </td>
                    <td class="py-3.5 px-4 font-medium text-slate-600 whitespace-nowrap">
                        @if($order->end_date)
                            {{ $order->end_date->format('d/m/Y') }}
                        @else
                            <span class="text-slate-400 italic text-[11px]">Sin fecha</span>
                        @endif
                    </td>
                    <td class="py-3.5 px-4 whitespace-nowrap">
                        <div class="flex items-center gap-2">
                            <span class="w-6 h-6 rounded-full bg-orange-100 text-orange-700 font-bold text-[10px] flex items-center justify-center">
                                {{ strtoupper(substr($order->user->name ?? 'U', 0, 2)) }}
                            </span>
                            <span class="font-medium text-slate-700">{{ $order->user->name ?? 'Sin asignar' }}</span>
                        </div>
                    </td>
                    <td class="py-3.5 pl-4 text-right whitespace-nowrap">
                        @include('admin.components.dashboard.status-badge', ['status' => $order->status])
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="py-8 text-center text-slate-400 text-xs">
                        No se encontraron órdenes de producción registradas con los criterios seleccionados.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if(isset($orders) && method_exists($orders, 'hasPages') && $orders->hasPages())
        <div class="mt-4 pt-4 border-t border-slate-100">
            {{ $orders->links() }}
        </div>
    @endif
</div>