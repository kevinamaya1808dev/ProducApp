@php
    $role = $user->roles->first();
    $initials = collect(explode(' ', $user->name))->map(fn($n) => strtoupper(substr($n, 0, 1)))->take(2)->implode('');

    $ordersData = $user->productionOrders->map(function($order) {
        $name = $order->component_name
             ?? ($order->name
             ?? ($order->title
             ?? ($order->descripcion ?? ('Orden #' . $order->order_naumbrer))));

        return [
            'id' => $order->order_number,
            'component_name' => $name,
            'status' => $order->status ?? 'pendiente'
        ];
    });

    $activeOrder = $user->productionOrders->where('status', 'in_progress')->first();

    $currentOrderName = $activeOrder
        ? ($activeOrder->component_name ?? ($activeOrder->name ?? ($activeOrder->title ?? ('Orden #' . $activeOrder->order_number))))
        : 'Ninguna';
@endphp
<div
    class="user-card bg-white border border-slate-200 rounded-2xl p-5 shadow-sm hover:border-orange-300 hover:shadow-md transition-all cursor-pointer relative overflow-hidden group"
    data-id="{{ $user->id }}"
    data-name="{{ $user->name }}"
    data-email="{{ $user->email }}"
    data-role-id="{{ $role?->id }}"
    data-role-name="{{ $role?->name ?? 'Sin rol' }}"
    data-initials="{{ $initials }}"
    data-turno="{{ $user->turno ?? 'Sin asignar' }}"
    data-estacion="{{ $user->planta ?? 'N/A' }}"
    data-active="{{ $user->active }}"
    data-notas="{{ $user->notas ?? '' }}"
    data-created="{{ $user->created_at->translatedFormat('M Y') }}"
    data-skills="{{ $user->skills->pluck('skill') }}"
    data-permissions="{{ $user->permissions->pluck('id') }}"
    data-orders="{{ json_encode($ordersData) }}"
    data-current-order="{{ $currentOrderName }}"
    onclick="selectUser(this)"
>
    <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-orange-500 to-amber-500"></div>
    <div class="flex justify-between items-start mb-4">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 rounded-full bg-orange-600 text-white font-bold flex items-center justify-center text-sm shadow-sm">{{ $initials }}</div>
            <div>
                <h3 class="font-bold text-slate-900 text-base group-hover:text-orange-600 transition-colors">{{ $user->name }}</h3>
                <span class="inline-block bg-orange-50 text-orange-700 border border-orange-100 text-[10px] font-bold px-2 py-0.5 rounded-md mt-0.5">{{ $role?->name ?? 'Sin rol' }}</span>
            </div>
        </div>
    </div>
    <div class="pt-3 border-t border-slate-100 text-xs">
        <p class="text-slate-400 font-medium uppercase tracking-wider text-[10px]">Correo</p>
        <p class="font-bold text-slate-800 mt-0.5 truncate">{{ $user->email }}</p>
    </div>
</div>