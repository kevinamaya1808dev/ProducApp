@extends('layouts.app')

@section('content')

<div class="p-6 max-w-[1600px] mx-auto overflow-hidden">

    @include('admin.orders.components.header')

    @include('admin.orders.components.kpi-cards', ['orders' => $orders])

    @include('admin.orders.components.filters')

    <!-- Tabla + Panel -->
   <div class="flex items-start gap-6 relative">
    <div class="flex-1 min-w-0">
        @include('admin.orders.components.orders-table', ['orders' => $orders])
    </div>
    @include('admin.orders.components.detail-panel')
</div>
    <div class="mt-6">
        {{ $orders->links() }}
    </div>

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
        // Parsear las subórdenes enviadas desde el atributo de data
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

        // Llenar el contenedor de Subórdenes
        const container = document.getElementById('panelSubOrdersList');
        document.getElementById('panelSubOrdersCount').textContent = subOrders.length;
        container.innerHTML = '';

        if (subOrders.length === 0) {
            container.innerHTML = `<p class="text-xs text-slate-400 dark:text-stone-500 italic mt-2">No hay procesos desglosados.</p>`;
        } else {
            subOrders.forEach(sub => {
                container.innerHTML += `
                    <div class="p-2.5 rounded-xl bg-slate-50 dark:bg-stone-800/60 border border-slate-100 dark:border-stone-800 text-xs">
                        <div class="flex justify-between font-bold text-slate-800 dark:text-stone-200">
                            <span>${sub.proceso}</span>
                            <span class="text-orange-600 dark:text-orange-400">${sub.completed_pieces}/${sub.quantity} pzas</span>
                        </div>
                        <div class="flex justify-between items-center mt-1 text-[11px] text-slate-400 dark:text-stone-400">
                            <span>Op: <strong class="text-slate-600 dark:text-stone-300">${sub.user_name}</strong></span>
                            <span class="uppercase tracking-wider font-semibold text-[10px] bg-slate-200 dark:bg-stone-700 text-slate-700 dark:text-stone-300 px-1.5 py-0.5 rounded">${sub.estacion}</span>
                        </div>
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
            <option value="">Seleccionar Operario</option>
            @foreach($operarios as $op)
                <option value="{{ $op->id }}">{{ $op->name }}</option>
            @endforeach
        `;

        const row = document.createElement('div');
        row.className = 'grid grid-cols-12 gap-2 bg-slate-50 dark:bg-stone-800/50 p-2.5 rounded-xl border border-slate-200 dark:border-stone-800 items-center suborder-row mb-2';
        row.innerHTML = `
            <div class="col-span-4">
                <input type="text" name="sub_orders[${index}][proceso]" value="${data?.proceso || ''}" placeholder="Ej. Ensamblaje" required class="w-full text-xs bg-white dark:bg-stone-900 border border-slate-200 dark:border-stone-700 rounded-lg px-2.5 py-1.5 text-slate-800 dark:text-stone-200 outline-none focus:ring-2 focus:ring-orange-600/50">
            </div>
            <div class="col-span-4">
                <select name="sub_orders[${index}][user_id]" class="w-full text-xs bg-white dark:bg-stone-900 border border-slate-200 dark:border-stone-700 rounded-lg px-2 py-1.5 text-slate-800 dark:text-stone-200 outline-none focus:ring-2 focus:ring-orange-600/50">
                    ${operariosOptions}
                </select>
            </div>
            <div class="col-span-3">
                <input type="number" name="sub_orders[${index}][quantity]" value="${data?.quantity || ''}" placeholder="Cant." min="1" required class="w-full text-xs bg-white dark:bg-stone-900 border border-slate-200 dark:border-stone-700 rounded-lg px-2 py-1.5 text-slate-800 dark:text-stone-200 outline-none focus:ring-2 focus:ring-orange-600/50">
            </div>
            <div class="col-span-1 text-right">
                <button type="button" onclick="this.closest('.suborder-row').remove()" class="text-red-500 hover:text-red-700 font-bold text-sm bg-red-50 dark:bg-red-500/10 w-6 h-6 rounded-full flex items-center justify-center ml-auto">&times;</button>
            </div>
        `;

        container.appendChild(row);

        if (data?.user_id) {
            row.querySelector(`select[name="sub_orders[${index}][user_id]"]`).value = data.user_id;
        }
    }

    // ===== Modal: Crear =====
    function openCreateModal() {
        // Limpiamos los contenedores de subórdenes
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

        // Cargar las subórdenes dinámicas
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