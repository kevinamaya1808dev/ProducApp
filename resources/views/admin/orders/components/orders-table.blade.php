<div class="bg-white dark:bg-stone-900 rounded-2xl border border-slate-200 dark:border-stone-800 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 dark:bg-stone-800/50 border-b border-slate-100 dark:border-stone-800 text-xs font-bold text-slate-500 dark:text-stone-400 uppercase tracking-wider">
                    <th class="p-4">Orden</th>
                    <th class="p-4">Producto</th>
                    <th class="p-4">Operario</th>
                    <th class="p-4">Progreso</th>
                    <th class="p-4">Estado</th>
                    <th class="p-4 text-right">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-stone-800 text-sm">
                @forelse($orders as $order)
                    @php
                        $totalPieces = $order->quantity;
                        $completedPieces = $order->subOrders->sum('completed_pieces') ?? 0;
                        $percentage = $totalPieces > 0 ? min(100, round(($completedPieces / $totalPieces) * 100)) : 0;
                        
                        $statusColors = [
                            'pending' => 'bg-amber-50 dark:bg-amber-950/40 text-amber-600 dark:text-amber-400 border-amber-200/50',
                            'in_progress' => 'bg-orange-50 dark:bg-orange-950/40 text-orange-600 dark:text-orange-400 border-orange-200/50',
                            'completed' => 'bg-emerald-50 dark:bg-emerald-950/40 text-emerald-600 dark:text-emerald-400 border-emerald-200/50',
                            'cancelled' => 'bg-red-50 dark:bg-red-950/40 text-red-600 dark:text-red-400 border-red-200/50',
                        ];
                        $statusLabels = [
                            'pending' => 'Pendiente',
                            'in_progress' => 'En Progreso',
                            'completed' => 'Completada',
                            'cancelled' => 'Cancelada',
                        ];
                        $priorityLabels = [
                            'low' => 'Baja',
                            'medium' => 'Media',
                            'high' => 'Alta',
                        ];
                    @endphp
                    <tr class="order-row hover:bg-slate-50/60 dark:hover:bg-stone-800/40 transition-colors cursor-pointer"
                        data-id="{{ $order->id }}"
                        data-order-number="{{ $order->order_number }}"
                        data-product-name="{{ $order->product->name ?? 'Producto eliminado' }}"
                        data-product-id="{{ $order->product_id }}"
                        data-category="{{ $order->product->category ?? 'General' }}"
                        data-quantity="{{ $order->quantity }}"
                        data-piezas="{{ $completedPieces }}"
                        data-porcentaje="{{ $percentage }}"
                        data-status="{{ $order->status }}"
                        data-status-label="{{ $statusLabels[$order->status] ?? $order->status }}"
                        data-priority="{{ $order->priority }}"
                        data-priority-label="{{ $priorityLabels[$order->priority] ?? $order->priority }}"
                        data-user-id="{{ $order->user_id }}"
                        data-user-name="{{ $order->user->name ?? 'Sin asignar' }}"
                        data-estacion="{{ $order->estacion ?? '' }}"
                        data-start-date="{{ $order->start_date ? \Carbon\Carbon::parse($order->start_date)->format('Y-m-d') : '' }}"
                        data-end-date="{{ $order->end_date ? \Carbon\Carbon::parse($order->end_date)->format('Y-m-d') : '' }}"
                        data-sub-orders='{{ json_encode($order->subOrders->map(fn($sub) => [
    "id" => $sub->id,
    "proceso" => $sub->proceso,
    "quantity" => $sub->quantity,
    "completed_pieces" => $sub->completed_pieces,
    "status" => $sub->status,
    "es_ensamblaje" => (bool) $sub->es_ensamblaje,
    "operarios" => $sub->assignedUsers->map(fn($u) => [
        "id" => $u->id,
        "nombre" => $u->name,
        "estacion" => $u->pivot->estacion ?: "Sin asignar",
        "aportadas" => $u->pivot->pieces_contributed,
    ]),
])) }}'
                        onclick="viewOrder(this)">
                        <td class="p-4 font-bold text-slate-900 dark:text-stone-100">
                            {{ $order->order_number }}
                            <span class="block text-xs font-normal text-slate-400">{{ $priorityLabels[$order->priority] ?? 'Media' }}</span>
                        </td>
                        <td class="p-4 text-slate-700 dark:text-stone-300">
                            {{ $order->product->name ?? 'N/A' }}
                            <span class="block text-xs text-slate-400">{{ $order->quantity }} pzas</span>
                        </td>
                        <td class="p-4 text-slate-700 dark:text-stone-300">
                            {{ $order->user->name ?? 'Sin asignar' }}
                            <span class="block text-xs text-slate-400">{{ $order->estacion ?? 'Sin estación' }}</span>
                        </td>
                        <td class="p-4 w-48">
                            <div class="flex justify-between text-xs mb-1 font-semibold text-slate-600 dark:text-stone-400">
                                <span>{{ $percentage }}%</span>
                                <span>{{ $completedPieces }}/{{ $order->quantity }}</span>
                            </div>
                            <div class="w-full bg-slate-100 dark:bg-stone-800 h-2 rounded-full overflow-hidden">
                                <div class="bg-orange-600 h-full rounded-full" style="width: {{ $percentage }}%"></div>
                            </div>
                        </td>
                        <td class="p-4">
                            <span class="px-2.5 py-1 rounded-lg text-xs font-bold border {{ $statusColors[$order->status] ?? 'bg-slate-100 text-slate-600' }}">
                                {{ $statusLabels[$order->status] ?? $order->status }}
                            </span>
                        </td>
                        <td class="p-4 text-right" onclick="event.stopPropagation()">
                            <div class="flex items-center justify-end gap-2">
                                <button type="button" onclick="viewOrder(this.closest('tr'))" class="p-2 bg-slate-100 dark:bg-stone-800 hover:bg-orange-50 dark:hover:bg-stone-700 text-slate-600 dark:text-stone-300 hover:text-orange-600 rounded-lg transition-colors" title="Ver detalles">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                </button>
                                @can('manage-orders')
                                    <button type="button" onclick="viewOrder(this.closest('tr')); openEditModalFromPanel();" class="p-2 bg-slate-100 dark:bg-stone-800 hover:bg-orange-50 dark:hover:bg-stone-700 text-slate-600 dark:text-stone-300 hover:text-orange-600 rounded-lg transition-colors" title="Editar orden">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    </button>
                                    <button type="button" onclick="viewOrder(this.closest('tr')); openDeleteModalFromPanel();" class="p-2 bg-slate-100 dark:bg-stone-800 hover:bg-red-50 dark:hover:bg-stone-700 text-slate-600 dark:text-stone-300 hover:text-red-600 rounded-lg transition-colors" title="Eliminar orden">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                @endcan
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="p-8 text-center text-slate-400 dark:text-stone-500">
                            No se encontraron órdenes de producción registradas.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>