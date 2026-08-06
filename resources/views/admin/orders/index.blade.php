@extends('layouts.app')

@section('content')
<div class="p-6 max-w-[1600px] mx-auto overflow-hidden">

    {{-- Header --}}
    @include('admin.orders.components.header')

    {{-- KPI Cards --}}
    @include('admin.orders.components.kpi-cards', ['orders' => $orders])

    {{-- Filters & Search --}}
    @include('admin.orders.components.filters')

    {{-- Tabla + Panel Lateral --}}
    <div class="flex items-start gap-6 relative">
        <div class="flex-1 min-w-0">
            @include('admin.orders.components.orders-table', ['orders' => $orders])
        </div>
        @include('admin.orders.components.detail-panel')
    </div>

    {{-- Paginación --}}
    <div class="mt-6">
        {{ $orders->links() }}
    </div>

    {{-- Modales (Solo para usuarios con permisos) --}}
    @can('manage-orders')
        @include('admin.orders.modals.create')
        @include('admin.orders.modals.edit')
        @include('admin.orders.modals.delete')
    @endcan

</div>
@endsection

@push('scripts')
<script>
    let currentOrder = null;
    let currentStatusFilter = 'all';

    // ===== Filtro por estado =====
    function setStatusFilter(status, btn) {
        currentStatusFilter = status;
        document.querySelectorAll('.status-filter-btn').forEach(b => {
            b.classList.remove('bg-orange-600', 'text-white', 'shadow-sm', 'shadow-orange-600/20');
            b.classList.add('bg-white', 'dark:bg-stone-900', 'border', 'border-slate-200', 'dark:border-stone-800', 'text-slate-600', 'dark:text-stone-300');
        });
        btn.classList.remove('bg-white', 'dark:bg-stone-900', 'border', 'border-slate-200', 'dark:border-stone-800', 'text-slate-600', 'dark:text-stone-300');
        btn.classList.add('bg-orange-600', 'text-white', 'shadow-sm', 'shadow-orange-600/20');
        filterOrders();
    }

    // ===== Búsqueda + filtro combinados =====
    function filterOrders() {
        const query = document.getElementById('searchInput').value.trim().toLowerCase();
        const rows = document.querySelectorAll('.order-row');

        rows.forEach(row => {
            const matchesSearch =
                row.dataset.orderNumber.toLowerCase().includes(query) ||
                row.dataset.productName.toLowerCase().includes(query) ||
                row.dataset.userName.toLowerCase().includes(query);

            const matchesStatus = currentStatusFilter === 'all' || row.dataset.status === currentStatusFilter;

            row.style.display = (matchesSearch && matchesStatus) ? '' : 'none';
        });
    }

    // ===== Panel lateral =====
    function viewOrder(row) {
        currentOrder = { ...row.dataset };
        const subOrders = JSON.parse(currentOrder.subOrders || '[]');

        document.getElementById('panelOrderNumber').textContent = currentOrder.orderNumber;
        document.getElementById('panelProduct').textContent = currentOrder.productName;
        document.getElementById('panelCategory').textContent = currentOrder.category;
        document.getElementById('panelProgress').textContent = currentOrder.porcentaje + '%';
        document.getElementById('panelProgressBar').style.width = currentOrder.porcentaje + '%';
        document.getElementById('panelProgressText').textContent = currentOrder.piezas + ' / ' + currentOrder.quantity + ' pzas';
        document.getElementById('panelStatus').textContent = currentOrder.statusLabel;
        document.getElementById('panelPriority').textContent = currentOrder.priorityLabel;
        document.getElementById('panelOperator').textContent = currentOrder.userName;
        document.getElementById('panelStation').textContent = currentOrder.estacion || 'Sin asignar';
        document.getElementById('panelDeadline').textContent = currentOrder.endDate || 'Sin fecha';

        const container = document.getElementById('panelSubOrdersList');
        document.getElementById('panelSubOrdersCount').textContent = subOrders.length;
        container.innerHTML = '';

        if (subOrders.length === 0) {
            container.innerHTML = `<p class="text-xs text-slate-400 dark:text-stone-500 italic mt-2">No hay procesos desglosados.</p>`;
        } else {
            subOrders.forEach(sub => {
    const operariosHtml = (sub.operarios || []).length
        ? sub.operarios.map(op => `
            <div class="flex justify-between items-center text-[11px] text-slate-500 dark:text-stone-400 pl-2 border-l-2 border-orange-200 dark:border-orange-500/30 mt-1">
                <span>${op.nombre} <span class="text-slate-400">· ${op.estacion}</span></span>
                <span class="font-semibold text-slate-600 dark:text-stone-300">${op.aportadas} pzas</span>
            </div>
        `).join('')
        : `<p class="text-[11px] text-slate-400 italic pl-2 mt-1">Sin operarios asignados</p>`;

    const restantes = sub.quantity - sub.completed_pieces;
    const alertaBadge = (restantes > 0 && restantes <= 3)
        ? `<span class="text-[9px] font-bold uppercase bg-amber-100 text-amber-700 px-1.5 py-0.5 rounded ml-1.5">¡Casi listo!</span>`
        : '';
    const ensamblajeBadge = sub.es_ensamblaje
        ? `<span class="text-[9px] font-bold uppercase bg-orange-100 text-orange-700 px-1.5 py-0.5 rounded ml-1.5">Ensamblaje</span>`
        : '';

    container.innerHTML += `
        <div class="p-2.5 rounded-xl bg-slate-50 dark:bg-stone-800/60 border border-slate-100 dark:border-stone-800 text-xs">
            <div class="flex justify-between items-center font-bold text-slate-800 dark:text-stone-200">
                <span>${sub.proceso} ${ensamblajeBadge} ${alertaBadge}</span>
                <span class="text-orange-600 dark:text-orange-400">${sub.completed_pieces}/${sub.quantity} pzas</span>
            </div>
            ${operariosHtml}
        </div>
    `;
});
        }

        document.getElementById('orderPanel').style.display = 'flex';
    }

    function closePanel() {
        document.getElementById('orderPanel').style.display = 'none';
        currentOrder = null;
    }

    // ===== Generador Dinámico de Subórdenes para Modales =====
    function addSubOrderRow(containerId, data = null) {
    const container = document.getElementById(containerId);
    const index = container.children.length;

    const operariosOptions = `
        @foreach($operarios ?? [] as $op)
            <option value="{{ $op->id }}">{{ $op->name }}</option>
        @endforeach
    `;

    const row = document.createElement('div');
    row.className = 'grid grid-cols-12 gap-2 bg-slate-50 dark:bg-stone-800/50 p-2.5 rounded-xl border border-slate-200 dark:border-stone-800 items-start suborder-row mb-2';
    row.innerHTML = `
        <div class="col-span-4">
            <input type="text" name="sub_orders[${index}][proceso]" value="${data?.proceso || ''}" placeholder="Ej. Ensamblaje" required class="w-full text-xs bg-white dark:bg-stone-900 border border-slate-200 dark:border-stone-700 rounded-lg px-2.5 py-1.5 text-slate-800 dark:text-stone-200 outline-none focus:ring-2 focus:ring-orange-600/50">
            <label class="flex items-center gap-1.5 mt-1.5 text-[11px] text-slate-500 dark:text-stone-400 cursor-pointer select-none">
                <input type="checkbox" name="sub_orders[${index}][es_ensamblaje]" value="1" ${data?.es_ensamblaje ? 'checked' : ''} class="rounded border-slate-300 text-orange-600 focus:ring-orange-500">
                Fase final (ensamblaje) — suma al stock
            </label>
        </div>
        <div class="col-span-4">
            <select name="sub_orders[${index}][operarios][]" multiple size="3" class="w-full text-xs bg-white dark:bg-stone-900 border border-slate-200 dark:border-stone-700 rounded-lg px-2 py-1.5 text-slate-800 dark:text-stone-200 outline-none focus:ring-2 focus:ring-orange-600/50">
                ${operariosOptions}
            </select>
            <p class="text-[10px] text-slate-400 mt-1">Ctrl/Cmd + clic para elegir varios</p>
        </div>
        <div class="col-span-3">
            <input type="number" name="sub_orders[${index}][quantity]" value="${data?.quantity || ''}" placeholder="Cant." min="1" required class="w-full text-xs bg-white dark:bg-stone-900 border border-slate-200 dark:border-stone-700 rounded-lg px-2 py-1.5 text-slate-800 dark:text-stone-200 outline-none focus:ring-2 focus:ring-orange-600/50">
        </div>
        <div class="col-span-1 text-right">
            <button type="button" onclick="this.closest('.suborder-row').remove()" class="text-red-500 hover:text-red-700 font-bold text-sm bg-red-50 dark:bg-red-500/10 w-6 h-6 rounded-full flex items-center justify-center ml-auto">&times;</button>
        </div>
    `;

    container.appendChild(row);

    if (data?.operarios?.length) {
        const select = row.querySelector(`select[name="sub_orders[${index}][operarios][]"]`);
        data.operarios.forEach(id => {
            const opt = select.querySelector(`option[value="${id}"]`);
            if (opt) opt.selected = true;
        });
    }
}

    // ===== Modal: Crear =====
    function openCreateModal() {
        const container = document.getElementById('createSubOrdersContainer');
        if(container) container.innerHTML = '';
        document.getElementById('createOrderModal').style.display = 'block';
    }
    function closeCreateModal() {
        document.getElementById('createOrderModal').style.display = 'none';
    }

    // ===== Modal: Editar =====
    function openEditModalFromPanel() {
        if (!currentOrder) return;
        document.getElementById('editOrderForm').action = '/admin/orders/' + currentOrder.id;
        document.getElementById('editOrderNumber').value = currentOrder.orderNumber;
        document.getElementById('editProductId').value = currentOrder.productId;
        document.getElementById('editQuantity').value = currentOrder.quantity;
        document.getElementById('editStatus').value = currentOrder.status;
        document.getElementById('editPriority').value = currentOrder.priority;
        document.getElementById('editUserId').value = currentOrder.userId;
        document.getElementById('editEstacion').value = currentOrder.estacion || '';
        document.getElementById('editStartDate').value = currentOrder.startDate || '';
        document.getElementById('editEndDate').value = currentOrder.endDate || '';

        const editContainer = document.getElementById('editSubOrdersContainer');
        if (editContainer) {
            editContainer.innerHTML = '';
            const subOrders = JSON.parse(currentOrder.subOrders || '[]');
            subOrders.forEach(sub => addSubOrderRow('editSubOrdersContainer', sub));
        }

        document.getElementById('editOrderModal').style.display = 'block';
    }
    function closeEditModal() {
        document.getElementById('editOrderModal').style.display = 'none';
    }

    // ===== Modal: Eliminar =====
    function openDeleteModalFromPanel() {
        if (!currentOrder) return;
        document.getElementById('deleteOrderForm').action = '/admin/orders/' + currentOrder.id;
        document.getElementById('deleteOrderNumber').textContent = currentOrder.orderNumber;
        document.getElementById('deleteOrderModal').style.display = 'block';
    }
    function closeDeleteModal() {
        document.getElementById('deleteOrderModal').style.display = 'none';
    }

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            closeCreateModal();
            closeEditModal();
            closeDeleteModal();
        }
    });
</script>
@endpush