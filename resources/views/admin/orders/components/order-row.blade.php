@php
    $priorityColors = [
        'high' => 'bg-red-50 dark:bg-red-500/10 text-red-600 dark:text-red-400 border-red-100 dark:border-red-500/20',
        'medium' => 'bg-yellow-50 dark:bg-yellow-500/10 text-yellow-700 dark:text-yellow-400 border-yellow-200 dark:border-yellow-500/20',
        'low' => 'bg-slate-50 dark:bg-stone-800 text-slate-600 dark:text-stone-300 border-slate-200 dark:border-stone-700',
    ];
    $statusColors = [
        'pending' => 'bg-white dark:bg-stone-900 border-slate-200 dark:border-stone-800 text-slate-600 dark:text-stone-300',
        'in_progress' => 'bg-white dark:bg-stone-900 border-slate-200 dark:border-stone-800 text-orange-600 dark:text-orange-400',
        'completed' => 'bg-emerald-50 dark:bg-emerald-500/10 border-emerald-200 dark:border-emerald-500/20 text-emerald-700 dark:text-emerald-400',
        'cancelled' => 'bg-red-50 dark:bg-red-500/10 border-red-200 dark:border-red-500/20 text-red-700 dark:text-red-400',
    ];
    $statusDot = [
        'pending' => 'bg-slate-400 dark:bg-stone-500',
        'in_progress' => 'bg-orange-600 dark:bg-orange-400',
        'completed' => 'bg-emerald-500 dark:bg-emerald-400',
        'cancelled' => 'bg-red-500 dark:bg-red-400',
    ];
    $isOverdue = $order->end_date && $order->end_date->isPast() && $order->status !== 'completed';
    $operarioInitials = $order->user
        ? collect(explode(' ', $order->user->name))->map(fn($n) => strtoupper(substr($n, 0, 1)))->take(2)->implode('')
        : '--';
@endphp
<tr class="order-row border-b border-slate-50 dark:border-stone-800/60 hover:bg-slate-50/50 dark:hover:bg-stone-800/50 transition-colors"
    data-id="{{ $order->id }}"
    data-order-number="{{ $order->order_number }}"
    data-product-id="{{ $order->product_id }}"
    data-product-name="{{ $order->product->name ?? 'Sin producto' }}"
    data-category="{{ $order->product->category->name ?? '' }}"
    data-quantity="{{ $order->quantity }}"
    data-piezas="{{ $order->piezas_registradas }}"
    data-porcentaje="{{ round($order->porcentaje_avance) }}"
    data-status="{{ $order->status }}"
    data-status-label="{{ $order->status_label }}"
    data-priority="{{ $order->priority }}"
    data-priority-label="{{ $order->priority_label }}"
    data-user-id="{{ $order->user_id }}"
    data-user-name="{{ $order->user->name ?? 'Sin asignar' }}"
    data-estacion="{{ $order->estacion }}"
    data-start-date="{{ $order->start_date?->format('Y-m-d') }}"
    data-end-date="{{ $order->end_date?->format('Y-m-d') }}"
>
    <td class="px-5 py-4">
        <span class="bg-orange-50 dark:bg-orange-500/10 text-orange-600 dark:text-orange-400 border border-orange-100 dark:border-orange-500/20 text-xs font-bold px-2.5 py-1 rounded-md">{{ $order->order_number }}</span>
    </td>
    <td class="px-5 py-4">
        <p class="font-bold text-slate-900 dark:text-stone-100">{{ $order->product->name ?? 'Sin producto' }}</p>
        <p class="text-xs text-slate-400 dark:text-stone-500 mt-0.5">{{ $order->product->category->name ?? '' }}</p>
    </td>
    <td class="px-5 py-4">
        <span class="{{ $priorityColors[$order->priority] }} border text-[11px] font-bold px-2 py-0.5 rounded-full">{{ $order->priority_label }}</span>
    </td>
    <td class="px-5 py-4">
        <div class="flex items-center gap-3">
            <div class="flex-1 h-1.5 bg-slate-100 dark:bg-stone-800 rounded-full overflow-hidden">
                <div class="h-full bg-orange-600 rounded-full" style="width: {{ round($order->porcentaje_avance) }}%"></div>
            </div>
            <span class="text-xs font-semibold text-slate-600 dark:text-stone-300">{{ round($order->porcentaje_avance) }}%</span>
        </div>
        <p class="text-[10px] font-medium text-slate-400 dark:text-stone-500 mt-1 tracking-wider">{{ $order->piezas_registradas }}/{{ $order->quantity }} pzas</p>
    </td>
    <td class="px-5 py-4 text-xs font-semibold {{ $isOverdue ? 'text-red-600 dark:text-red-400' : 'text-slate-600 dark:text-stone-300' }}">
        @if($isOverdue)
            <span class="flex items-center gap-1">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                {{ $order->end_date?->format('d M Y') ?? 'Sin fecha' }}
            </span>
        @else
            {{ $order->end_date?->format('d M Y') ?? 'Sin fecha' }}
        @endif
    </td>
    <td class="px-5 py-4">
        <div class="flex items-center gap-2">
            <span class="w-6 h-6 rounded-full bg-orange-100 dark:bg-orange-500/20 text-orange-700 dark:text-orange-300 flex items-center justify-center text-[10px] font-bold">{{ $operarioInitials }}</span>
            <span class="text-xs font-semibold text-slate-700 dark:text-stone-200">{{ $order->user->name ?? 'Sin asignar' }}</span>
        </div>
    </td>
    <td class="px-5 py-4">
        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold {{ $statusColors[$order->status] }} border">
            <span class="w-1.5 h-1.5 rounded-full {{ $statusDot[$order->status] }}"></span> {{ $order->status_label }}
        </span>
    </td>
    <td class="px-5 py-4 text-right">
        <button type="button" onclick="viewOrder(this.closest('tr'))" class="text-orange-600 dark:text-orange-400 hover:text-orange-800 dark:hover:text-orange-300 text-xs font-bold transition-colors">Ver &rarr;</button>
    </td>
</tr>