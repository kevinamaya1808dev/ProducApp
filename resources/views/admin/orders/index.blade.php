@extends('layouts.app')

@section('content')

<div class="p-6 max-w-[1600px] mx-auto overflow-hidden">

    @include('admin.orders.components.flash-messages')

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
            b.classList.add('bg-white', 'border', 'border-slate-200', 'text-slate-600');
        });
        btn.classList.remove('bg-white', 'border', 'border-slate-200', 'text-slate-600');
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

        document.getElementById('orderPanel').style.display = 'flex';
    }

    function closePanel() {
        document.getElementById('orderPanel').style.display = 'none';
        currentOrder = null;
    }

    // ===== Modal: Crear =====
    function openCreateModal() {
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