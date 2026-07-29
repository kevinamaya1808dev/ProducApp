@extends('layouts.app')

@section('content')

<div class="min-h-screen bg-slate-50/50 p-6 max-w-[1600px] mx-auto overflow-hidden">

    @if(session('success'))
        <div class="mb-4 bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm font-medium px-4 py-3 rounded-lg">
            {{ session('success') }}
        </div>
    @endif
    @if($errors->any())
        <div class="mb-4 bg-red-50 border border-red-200 text-red-700 text-sm font-medium px-4 py-3 rounded-lg">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Encabezado -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Órdenes de Producción</h1>
            <p class="text-sm text-slate-500 mt-1">Gestión completa de órdenes &middot; {{ $orders->total() }} registro(s)</p>
        </div>

        @can('manage-orders')
        <button type="button" onclick="openCreateModal()" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-lg text-sm font-semibold transition-all shadow-sm shadow-blue-600/30 flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Nueva Orden
        </button>
        @endcan
    </div>

    <!-- KPI Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-white border border-slate-200 rounded-xl p-5 shadow-sm">
            <p class="text-xs font-bold text-slate-500 tracking-wider uppercase mb-1">Total Órdenes</p>
            <p class="text-3xl font-black text-slate-900">{{ $orders->total() }}</p>
        </div>
        <div class="bg-white border border-slate-200 rounded-xl p-5 shadow-sm">
            <p class="text-xs font-bold text-slate-500 tracking-wider uppercase mb-1">En Progreso</p>
            <p class="text-3xl font-black text-blue-600">{{ $orders->where('status', 'in_progress')->count() }}</p>
        </div>
        <div class="bg-white border border-slate-200 rounded-xl p-5 shadow-sm">
            <p class="text-xs font-bold text-slate-500 tracking-wider uppercase mb-1">Completadas</p>
            <p class="text-3xl font-black text-emerald-500">{{ $orders->where('status', 'completed')->count() }}</p>
        </div>
        <div class="bg-white border border-slate-200 rounded-xl p-5 shadow-sm">
            <p class="text-xs font-bold text-slate-500 tracking-wider uppercase mb-1">Urgentes (Alta)</p>
            <p class="text-3xl font-black text-red-600">{{ $orders->where('priority', 'high')->where('status', '!=', 'completed')->count() }}</p>
        </div>
    </div>

    <!-- Búsqueda y Filtros -->
    <div class="flex flex-col lg:flex-row gap-4 mb-6 items-start lg:items-center">
        <div class="bg-white p-2.5 rounded-xl border border-slate-200 shadow-sm relative w-full lg:w-96 shrink-0">
            <svg class="w-4 h-4 text-slate-400 absolute left-4 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            <input type="text" id="searchInput" oninput="filterOrders()" placeholder="Buscar por ID, producto u operario..." class="w-full pl-8 pr-2 text-sm bg-transparent border-none focus:ring-0 text-slate-700 placeholder-slate-400 outline-none">
        </div>

        <div class="flex flex-wrap gap-2" id="statusFilters">
            <button type="button" onclick="setStatusFilter('all', this)" class="status-filter-btn px-4 py-1.5 rounded-lg text-sm font-medium bg-blue-600 text-white shadow-sm shadow-blue-600/20">Todos</button>
            <button type="button" onclick="setStatusFilter('pending', this)" class="status-filter-btn px-4 py-1.5 rounded-lg text-sm font-medium bg-white border border-slate-200 text-slate-600 hover:bg-slate-50 transition-colors">Pendiente</button>
            <button type="button" onclick="setStatusFilter('in_progress', this)" class="status-filter-btn px-4 py-1.5 rounded-lg text-sm font-medium bg-white border border-slate-200 text-slate-600 hover:bg-slate-50 transition-colors">En Progreso</button>
            <button type="button" onclick="setStatusFilter('completed', this)" class="status-filter-btn px-4 py-1.5 rounded-lg text-sm font-medium bg-white border border-slate-200 text-slate-600 hover:bg-slate-50 transition-colors">Completada</button>
            <button type="button" onclick="setStatusFilter('cancelled', this)" class="status-filter-btn px-4 py-1.5 rounded-lg text-sm font-medium bg-white border border-slate-200 text-slate-600 hover:bg-slate-50 transition-colors">Cancelada</button>
        </div>
    </div>

    <!-- Tabla + Panel -->
    <div class="flex items-start gap-6 relative">

        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-x-auto flex-1 transition-all duration-300">
            <table class="w-full text-left text-sm whitespace-nowrap">
                <thead>
                    <tr class="text-slate-400 border-b border-slate-100 uppercase tracking-widest text-[10px] font-bold">
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
                <tbody class="text-slate-700" id="ordersTableBody">
                    @forelse($orders as $order)
                        @php
                            $priorityColors = [
                                'high' => 'bg-red-50 text-red-600 border-red-100',
                                'medium' => 'bg-yellow-50 text-yellow-700 border-yellow-200',
                                'low' => 'bg-slate-50 text-slate-600 border-slate-200',
                            ];
                            $statusColors = [
                                'pending' => 'bg-white border-slate-200 text-slate-600',
                                'in_progress' => 'bg-white border-slate-200 text-blue-600',
                                'completed' => 'bg-emerald-50 border-emerald-200 text-emerald-700',
                                'cancelled' => 'bg-red-50 border-red-200 text-red-700',
                            ];
                            $statusDot = [
                                'pending' => 'bg-slate-400',
                                'in_progress' => 'bg-blue-600',
                                'completed' => 'bg-emerald-500',
                                'cancelled' => 'bg-red-500',
                            ];
                            $isOverdue = $order->end_date && $order->end_date->isPast() && $order->status !== 'completed';
                            $operarioInitials = $order->user
                                ? collect(explode(' ', $order->user->name))->map(fn($n) => strtoupper(substr($n, 0, 1)))->take(2)->implode('')
                                : '--';
                        @endphp
                        <tr class="order-row border-b border-slate-50 hover:bg-slate-50/50 transition-colors"
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
                                <span class="bg-blue-50 text-blue-600 border border-blue-100 text-xs font-bold px-2.5 py-1 rounded-md">{{ $order->order_number }}</span>
                            </td>
                            <td class="px-5 py-4">
                                <p class="font-bold text-slate-900">{{ $order->product->name ?? 'Sin producto' }}</p>
                                <p class="text-xs text-slate-400 mt-0.5">{{ $order->product->category->name ?? '' }}</p>
                            </td>
                            <td class="px-5 py-4">
                                <span class="{{ $priorityColors[$order->priority] }} border text-[11px] font-bold px-2 py-0.5 rounded-full">{{ $order->priority_label }}</span>
                            </td>
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="flex-1 h-1.5 bg-slate-100 rounded-full overflow-hidden">
                                        <div class="h-full bg-blue-600 rounded-full" style="width: {{ round($order->porcentaje_avance) }}%"></div>
                                    </div>
                                    <span class="text-xs font-semibold text-slate-600">{{ round($order->porcentaje_avance) }}%</span>
                                </div>
                                <p class="text-[10px] font-medium text-slate-400 mt-1 tracking-wider">{{ $order->piezas_registradas }}/{{ $order->quantity }} pzas</p>
                            </td>
                            <td class="px-5 py-4 text-xs font-semibold {{ $isOverdue ? 'text-red-600' : 'text-slate-600' }}">
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
                                    <span class="w-6 h-6 rounded-full bg-slate-200 text-slate-600 flex items-center justify-center text-[10px] font-bold">{{ $operarioInitials }}</span>
                                    <span class="text-xs font-semibold text-slate-700">{{ $order->user->name ?? 'Sin asignar' }}</span>
                                </div>
                            </td>
                            <td class="px-5 py-4">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold {{ $statusColors[$order->status] }} border">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $statusDot[$order->status] }}"></span> {{ $order->status_label }}
                                </span>
                            </td>
                            <td class="px-5 py-4 text-right">
                                <button type="button" onclick="viewOrder(this.closest('tr'))" class="text-blue-600 hover:text-blue-800 text-xs font-bold transition-colors">Ver &rarr;</button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-sm text-slate-400 py-10">No hay órdenes de producción registradas.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Panel Lateral -->
        <div id="orderPanel" class="w-full max-w-[340px] shrink-0 bg-white border border-slate-200 rounded-2xl shadow-sm hidden lg:flex flex-col relative" style="display:none;">

            <div class="p-5 border-b border-slate-100 flex justify-between items-center bg-slate-50/50 rounded-t-2xl">
                <h3 class="font-bold text-slate-900 text-sm">Detalle de Orden</h3>
                <button type="button" onclick="closePanel()" class="text-slate-400 hover:text-slate-600">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <div class="p-5 flex-1 overflow-y-auto">
                <span id="panelOrderNumber" class="bg-blue-50 text-blue-600 border border-blue-100 text-[10px] font-bold px-2 py-0.5 rounded-md mb-3 inline-block"></span>
                <h2 id="panelProduct" class="text-lg font-black text-slate-900 leading-tight"></h2>
                <p id="panelCategory" class="text-xs text-slate-400 mt-1"></p>

                <div class="my-8 text-center bg-slate-50 p-6 rounded-xl border border-slate-100">
                    <div id="panelProgress" class="text-5xl font-black text-slate-900 tracking-tighter">0%</div>
                    <div class="w-full h-2 bg-slate-200 rounded-full overflow-hidden mt-4 mb-2">
                        <div id="panelProgressBar" class="h-full bg-blue-600 rounded-full transition-all duration-500" style="width: 0%"></div>
                    </div>
                    <div id="panelProgressText" class="text-xs font-bold text-slate-500 tracking-widest"></div>
                </div>

                <ul class="space-y-4">
                    <li class="flex justify-between items-center text-sm border-b border-slate-50 pb-3">
                        <span class="text-slate-400 font-semibold text-xs tracking-wider uppercase">Estado</span>
                        <span id="panelStatus" class="text-slate-800 font-bold"></span>
                    </li>
                    <li class="flex justify-between items-center text-sm border-b border-slate-50 pb-3">
                        <span class="text-slate-400 font-semibold text-xs tracking-wider uppercase">Prioridad</span>
                        <span id="panelPriority" class="text-slate-800 font-bold"></span>
                    </li>
                    <li class="flex justify-between items-center text-sm border-b border-slate-50 pb-3">
                        <span class="text-slate-400 font-semibold text-xs tracking-wider uppercase">Operario</span>
                        <span id="panelOperator" class="text-slate-800 font-bold"></span>
                    </li>
                    <li class="flex justify-between items-center text-sm border-b border-slate-50 pb-3">
                        <span class="text-slate-400 font-semibold text-xs tracking-wider uppercase">Estación</span>
                        <span id="panelStation" class="text-slate-800 font-bold"></span>
                    </li>
                    <li class="flex justify-between items-center text-sm">
                        <span class="text-slate-400 font-semibold text-xs tracking-wider uppercase">Fecha Límite</span>
                        <span id="panelDeadline" class="text-slate-800 font-bold"></span>
                    </li>
                </ul>
            </div>

            <div class="p-4 border-t border-slate-100 bg-slate-50/50 rounded-b-2xl flex gap-2">
                @can('manage-orders')
                <button type="button" onclick="openEditModalFromPanel()" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-semibold text-sm py-2.5 rounded-lg transition-colors shadow-sm">
                    Editar
                </button>
                <button type="button" onclick="openDeleteModalFromPanel()" class="bg-red-50 hover:bg-red-100 text-red-600 font-semibold text-sm py-2.5 px-4 rounded-lg transition-colors shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                </button>
                @else
                <div class="text-center text-xs text-slate-400 py-1 w-full">Modo visualización (Sin privilegios de edición)</div>
                @endcan
            </div>
        </div>
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
            b.classList.remove('bg-blue-600', 'text-white', 'shadow-sm', 'shadow-blue-600/20');
            b.classList.add('bg-white', 'border', 'border-slate-200', 'text-slate-600');
        });
        btn.classList.remove('bg-white', 'border', 'border-slate-200', 'text-slate-600');
        btn.classList.add('bg-blue-600', 'text-white', 'shadow-sm', 'shadow-blue-600/20');
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