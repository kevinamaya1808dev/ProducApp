<div class="xl:col-span-3 bg-stone-50 dark:bg-stone-900 border border-stone-200 dark:border-stone-800 rounded-xl shadow-sm p-6">
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 mb-6">
        <div>
            <h2 class="text-base font-bold text-stone-900 dark:text-stone-100">Órdenes de Producción</h2>
            <p class="text-xs text-stone-500 dark:text-stone-400">{{ isset($orders) ? $orders->total() : 0 }} órdenes registradas</p>
        </div>

        <div class="flex flex-wrap items-center gap-3">
            <form method="GET" action="{{ route('admin.dashboard') }}" class="relative w-full sm:w-64">
                @if(request('status'))
                    <input type="hidden" name="status" value="{{ request('status') }}">
                @endif
                @if(request('date'))
                    <input type="hidden" name="date" value="{{ request('date') }}">
                @endif
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-stone-400 dark:text-stone-500">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Buscar por número, producto..." class="w-full pl-9 pr-3 py-1.5 bg-stone-100 dark:bg-stone-800 border border-stone-200 dark:border-stone-700 rounded-lg text-xs text-stone-800 dark:text-stone-100 placeholder-stone-400 dark:placeholder-stone-500 focus:outline-none focus:ring-2 focus:ring-orange-500">
            </form>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="border-b border-stone-200 dark:border-stone-800 text-[11px] font-bold text-stone-400 dark:text-stone-400 uppercase tracking-wider">
                    <th class="pb-3 pr-2 w-8"></th>
                    <th class="pb-3 pr-4">ID de Orden</th>
                    <th class="pb-3 px-4">Producto</th>
                    <th class="pb-3 px-4">Progreso</th>
                    <th class="pb-3 px-4">Fecha Límite</th>
                    <th class="pb-3 px-4">Operario</th>
                    <th class="pb-3 pl-4 text-right">Estado</th>
                </tr>
            </thead>
            @forelse($orders ?? [] as $order)
            @php
                $rawProgress = $order->porcentaje_avance ?? $order->progress ?? 0;
                $progress = round($rawProgress);
                $subOrdersList = $order->subOrders ?? collect();
            @endphp
            <tbody
                x-data="{ open: false }"
                class="divide-y divide-stone-200 dark:divide-stone-800 text-xs text-stone-700 dark:text-stone-300"
            >
                <tr class="hover:bg-stone-100/60 dark:hover:bg-stone-800/50 transition-colors">
                    <td class="py-3.5 pr-2 align-middle">
                        @if($subOrdersList->count() > 0)
                            <button
                                type="button"
                                @click="open = !open"
                                aria-label="Ver subórdenes"
                                class="w-6 h-6 flex items-center justify-center rounded-md text-stone-400 dark:text-stone-500 hover:bg-stone-200 dark:hover:bg-stone-800 hover:text-orange-600 dark:hover:text-orange-400 transition-colors cursor-pointer"
                            >
                                <svg class="w-4 h-4 transition-transform duration-200" :class="open ? 'rotate-90' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </button>
                        @endif
                    </td>
                    <td class="py-3.5 pr-4 font-semibold text-orange-600 dark:text-orange-400 whitespace-nowrap">
                        {{ $order->order_number ?? $order->order_code ?? 'ORD-'.$order->id }}
                    </td>
                    <td class="py-3.5 px-4">
                        <p class="font-bold text-stone-800 dark:text-stone-200">{{ $order->product->name ?? 'Producto N/D' }}</p>
                        <p class="text-[11px] text-stone-400 dark:text-stone-500">{{ $order->product->category->name ?? 'General' }}</p>
                    </td>
                    <td class="py-3.5 px-4 w-40">
                        <div class="flex items-center gap-2">
                            <div class="flex-1 bg-stone-200 dark:bg-stone-800 rounded-full h-2 overflow-hidden">
                                <div class="bg-orange-500 h-2 rounded-full transition-all duration-300" style="width: {{ $progress }}%"></div>
                            </div>
                            <span class="text-[11px] font-semibold text-stone-500 dark:text-stone-400 w-8 text-right">{{ $progress }}%</span>
                        </div>
                    </td>
                    <td class="py-3.5 px-4 font-medium text-stone-600 dark:text-stone-400 whitespace-nowrap">
                        @if($order->end_date)
                            {{ $order->end_date->format('d/m/Y') }}
                        @else
                            <span class="text-stone-400 dark:text-stone-500 italic text-[11px]">Sin fecha</span>
                        @endif
                    </td>
                    <td class="py-3.5 px-4 whitespace-nowrap">
                        <div class="flex items-center gap-2">
                            <span class="w-6 h-6 rounded-full bg-orange-100 dark:bg-orange-950/60 text-orange-700 dark:text-orange-300 font-bold text-[10px] flex items-center justify-center">
                                {{ strtoupper(substr($order->user->name ?? 'U', 0, 2)) }}
                            </span>
                            <span class="font-medium text-stone-700 dark:text-stone-300">{{ $order->user->name ?? 'Sin asignar' }}</span>
                        </div>
                    </td>
                    <td class="py-3.5 pl-4 text-right whitespace-nowrap">
                        @include('admin.components.dashboard.status-badge', ['status' => $order->status])
                    </td>
                </tr>

                {{-- Fila expandible: subórdenes (fases) de esta orden y sus operarios asignados,
                     presentadas como una línea de tiempo vertical en vez de tarjetas planas --}}
                @if($subOrdersList->count() > 0)
                <tr x-show="open" x-cloak
                    x-transition:enter="transition ease-out duration-200 motion-reduce:transition-none"
                    x-transition:enter-start="opacity-0 -translate-y-1"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    x-transition:leave="transition ease-in duration-100 motion-reduce:transition-none"
                    x-transition:leave-start="opacity-100 translate-y-0"
                    x-transition:leave-end="opacity-0 -translate-y-1"
                >
                    <td colspan="7" class="bg-gradient-to-b from-stone-100/80 to-transparent dark:from-stone-800/20 dark:to-transparent px-4 py-4">
                        <div class="pl-6 pr-2">
                            <div class="relative space-y-3 before:absolute before:top-2 before:bottom-2 before:left-[15px] before:w-0.5 before:bg-gradient-to-b before:from-orange-300 before:via-stone-300 before:to-transparent dark:before:from-orange-800 dark:before:via-stone-700 dark:before:to-transparent">
                                @foreach($subOrdersList as $subOrder)
                                    @php
                                        $subProgress = round($subOrder->porcentaje_avance ?? 0);
                                        $assigned = $subOrder->assignedUsers ?? collect();
                                        $nodeClasses = match($subOrder->status) {
                                            'completed'   => 'bg-emerald-500 ring-emerald-100 dark:ring-emerald-950/60',
                                            'in_progress' => 'bg-orange-500 ring-orange-100 dark:ring-orange-950/60',
                                            default       => 'bg-stone-300 dark:bg-stone-600 ring-stone-100 dark:ring-stone-800',
                                        };
                                    @endphp
                                    <div class="relative pl-10 group">
                                        {{-- Nodo de la línea de tiempo --}}
                                        <span class="absolute left-0 top-1/2 -translate-y-1/2 w-8 h-8 rounded-full ring-4 {{ $nodeClasses }} flex items-center justify-center shadow-sm shrink-0">
                                            @if($subOrder->status === 'completed')
                                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                            @elseif($subOrder->es_ensamblaje)
                                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7h-9m3-3l3 3-3 3M4 17h9m-3 3l-3-3 3-3"></path></svg>
                                            @else
                                                <span class="text-[11px] font-bold text-white">{{ $loop->iteration }}</span>
                                            @endif
                                        </span>

                                        {{-- Tarjeta de la fase --}}
                                        <div class="flex flex-col sm:flex-row sm:items-center gap-3 bg-stone-50 dark:bg-stone-900 border border-stone-200 dark:border-stone-800 rounded-xl px-4 py-3 shadow-sm group-hover:shadow-md group-hover:-translate-y-0.5 motion-reduce:group-hover:translate-y-0 transition-all duration-200">
                                            <div class="sm:w-40 shrink-0">
                                                <p class="text-xs font-bold text-stone-800 dark:text-stone-200">
                                                    {{ $subOrder->proceso }}
                                                    @if($subOrder->es_ensamblaje)
                                                        <span class="ml-1 inline-block text-[9px] font-semibold text-orange-600 dark:text-orange-400 align-middle bg-orange-50 dark:bg-orange-950/40 px-1.5 py-0.5 rounded">Fase final</span>
                                                    @endif
                                                </p>
                                                <p class="text-[11px] text-stone-400 dark:text-stone-500">{{ $subOrder->completed_pieces }}/{{ $subOrder->quantity }} pzas</p>
                                            </div>

                                            <div class="flex-1 flex items-center gap-2 sm:max-w-xs">
                                                <div class="flex-1 bg-stone-200 dark:bg-stone-800 rounded-full h-1.5 overflow-hidden">
                                                    <div class="bg-gradient-to-r from-orange-400 to-orange-500 h-1.5 rounded-full transition-all duration-300" style="width: {{ $subProgress }}%"></div>
                                                </div>
                                                <span class="text-[10px] font-semibold text-stone-500 dark:text-stone-400 w-7 text-right">{{ $subProgress }}%</span>
                                            </div>

                                            <div class="shrink-0">
                                                @include('admin.components.dashboard.status-badge', ['status' => $subOrder->status])
                                            </div>

                                            <div class="flex items-center -space-x-2 shrink-0">
                                                @forelse($assigned as $person)
                                                    <span
                                                        title="{{ $person->name }}{{ $person->pivot->estacion ? ' · '.$person->pivot->estacion : '' }}"
                                                        class="w-6 h-6 rounded-full bg-orange-100 dark:bg-orange-950/60 text-orange-700 dark:text-orange-300 font-bold text-[10px] flex items-center justify-center border-2 border-stone-50 dark:border-stone-900 hover:z-10 hover:scale-110 motion-reduce:hover:scale-100 transition-transform"
                                                    >
                                                        {{ strtoupper(substr($person->name ?? 'U', 0, 2)) }}
                                                    </span>
                                                @empty
                                                    <span class="text-[11px] text-stone-400 dark:text-stone-500 italic">Sin operarios asignados</span>
                                                @endforelse
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </td>
                </tr>
                @endif
            </tbody>
            @empty
            <tbody>
                <tr>
                    <td colspan="7" class="py-8 text-center text-stone-400 dark:text-stone-500 text-xs">
                        No se encontraron órdenes de producción registradas con los criterios seleccionados.
                    </td>
                </tr>
            </tbody>
            @endforelse
        </table>
    </div>

    @if(isset($orders) && method_exists($orders, 'hasPages') && $orders->hasPages())
        <div class="mt-4 pt-4 border-t border-stone-200 dark:border-stone-800">
            {{ $orders->links() }}
        </div>
    @endif
</div>