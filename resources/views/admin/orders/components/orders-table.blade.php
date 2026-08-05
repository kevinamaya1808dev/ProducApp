<div class="bg-white dark:bg-stone-900 rounded-2xl border border-slate-200 dark:border-stone-800 shadow-sm overflow-x-auto flex-1 transition-all duration-300">
    <table class="w-full text-left text-sm whitespace-nowrap">
        <thead>
            <tr class="text-slate-400 dark:text-stone-500 border-b border-slate-100 dark:border-stone-800 uppercase tracking-widest text-[10px] font-bold">
                <th class="px-5 py-4">ID de Orden</th>
                <th class="px-5 py-4">Producto</th>
                <th class="px-5 py-4">Prioridad</th>
                <th class="px-5 py-4 min-w-[180px]">Progreso</th>
                <th class="px-5 py-4">Fecha Límite</th>
                <th class="px-5 py-4">Operario</th>
                <th class="px-5 py-4">Estado</th>
                <th class="px-5 py-4"></th>
            </tr>
        </thead>
        <tbody class="text-slate-700 dark:text-stone-300" id="ordersTableBody">
            @forelse($orders as $order)
                @include('admin.orders.components.order-row', ['order' => $order])
            @empty
                <tr>
                    <td colspan="8" class="text-center text-sm text-slate-400 dark:text-stone-500 py-10">No hay órdenes de producción registradas.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>