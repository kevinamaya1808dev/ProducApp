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
@can('orders.create')
    @include('admin.orders.modals.create')
@endcan
@can('orders.manage')
    @include('admin.orders.modals.create-sub-order')
    @include('admin.orders.modals.edit-sub-order')
    @include('admin.orders.modals.delete-sub-order')
@endcan
@can('orders.edit')
    @include('admin.orders.modals.edit')
@endcan
@can('orders.delete')
    @include('admin.orders.modals.delete')
@endcan

</div>
@endsection

@push('scripts')
<script>
    let currentOrder = null;
    let currentStatusFilter = 'all';
    let currentSubOrders = []; // Almacenará las subórdenes de la orden seleccionada actual

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

    // ===== Búsqueda + Filtro combinados =====
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

    // ===== Panel Lateral =====
    function viewOrder(row) {
        currentOrder = { ...row.dataset };
        currentSubOrders = JSON.parse(currentOrder.subOrders || '[]'); // Guardamos de forma segura en memoria

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
        document.getElementById('panelSubOrdersCount').textContent = currentSubOrders.length;
        container.innerHTML = '';

        if (currentSubOrders.length === 0) {
            container.innerHTML = `<p class="text-xs text-slate-400 dark:text-stone-500 italic mt-2">No hay procesos desglosados.</p>`;
        } else {
            currentSubOrders.forEach(sub => {
                const operariosHtml = (sub.operarios || []).length
                    ? sub.operarios.map(op => `
                        <div class="flex justify-between items-center text-[11px] text-slate-500 dark:text-stone-400 pl-2 border-l-2 border-orange-200 dark:border-orange-500/30 mt-1">
                            <span>${op.nombre} <span class="text-slate-400">· ${op.estacion || 'S/N'}</span></span>
                            <span class="font-semibold text-slate-600 dark:text-stone-300">${op.aportadas || 0} pzas</span>
                        </div>
                    `).join('')
                    : `<p class="text-[11px] text-slate-400 italic pl-2 mt-1">Sin operarios asignados</p>`;

                const restantes = sub.quantity - (sub.completed_pieces || sub.completed_quantity || 0);
                const alertaBadge = (restantes > 0 && restantes <= 3)
                    ? `<span class="text-[9px] font-bold uppercase bg-amber-100 text-amber-700 px-1.5 py-0.5 rounded ml-1.5">¡Casi listo!</span>`
                    : '';
                const ensamblajeBadge = sub.es_ensamblaje
                    ? `<span class="text-[9px] font-bold uppercase bg-orange-100 text-orange-700 px-1.5 py-0.5 rounded ml-1.5">Ensamblaje</span>`
                    : '';

                // Usamos únicamente el ID de la suborden para evitar errores de inyección de comillas en HTML
                container.innerHTML += `
                    <div class="p-2.5 rounded-xl bg-slate-50 dark:bg-stone-800/60 border border-slate-100 dark:border-stone-800 text-xs">
                        <div class="flex justify-between items-center font-bold text-slate-800 dark:text-stone-200 mb-1">
                            <span>${sub.proceso} ${ensamblajeBadge} ${alertaBadge}</span>
                            <div class="flex items-center gap-2">
                                <span class="text-orange-600 dark:text-orange-400">${sub.completed_pieces || sub.completed_quantity || 0}/${sub.quantity} pzas</span>
                                <button type="button" onclick="openEditSubOrderModalById(${sub.id})" class="text-slate-400 hover:text-orange-600 dark:hover:text-orange-400 p-0.5" title="Editar proceso">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 210.3H3v-3.572L16.732 3.732z"></path></svg>
                                </button>
                                <button type="button" onclick="openDeleteSubOrderModal(${sub.id}, '${sub.proceso}')" class="text-slate-400 hover:text-red-600 p-0.5" title="Eliminar proceso">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </div>
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
        currentSubOrders = [];
    }

    // ===== Generador Dinámico para Formulario Inicial de Orden =====
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
                    Fase final (ensamblaje)
                </label>
            </div>
            <div class="col-span-4">
                <select name="sub_orders[${index}][operarios][]" multiple size="3" class="w-full text-xs bg-white dark:bg-stone-900 border border-slate-200 dark:border-stone-700 rounded-lg px-2 py-1.5 text-slate-800 dark:text-stone-200 outline-none focus:ring-2 focus:ring-orange-600/50">
                    ${operariosOptions}
                </select>
                <p class="text-[10px] text-slate-400 mt-1">Ctrl/Cmd + clic para varios</p>
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
            data.operarios.forEach(op => {
                const opt = select.querySelector(`option[value="${op.id || op}"]`);
                if (opt) opt.selected = true;
            });
        }
    }

    // ===== Modales de Orden =====
    function openCreateModal() {
        const container = document.getElementById('createSubOrdersContainer');
        if (container) container.innerHTML = '';
        document.getElementById('createOrderModal').style.display = 'block';
    }
    function closeCreateModal() {
        document.getElementById('createOrderModal').style.display = 'none';
        window.dispatchEvent(new Event('closemodal'));
    }

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
        window.dispatchEvent(new Event('closemodal'));
    }

    function openDeleteModalFromPanel() {
        if (!currentOrder) return;
        document.getElementById('deleteOrderForm').action = '/admin/orders/' + currentOrder.id;
        document.getElementById('deleteOrderNumber').textContent = currentOrder.orderNumber;
        document.getElementById('deleteOrderModal').style.display = 'block';
    }
    function closeDeleteModal() {
        document.getElementById('deleteOrderModal').style.display = 'none';
    }

    // ===== Modales de SubOrden (CRUD de Procesos) =====
    function openCreateSubOrderModalFromPanel() {
        if (!currentOrder) return;
        document.getElementById('createSubOrderOrderId').value = currentOrder.id;
        document.getElementById('createSubOrderForm').reset();

        // El form.reset() desmarca los checkboxes de operarios, pero no todos los
        // navegadores disparan "change" al hacerlo — forzamos el evento para que el
        // contador "N seleccionados" (Alpine) vuelva a 0 en vez de quedar desfasado.
        const createOperariosContainer = document.getElementById('createSubOrderOperariosContainer');
        if (createOperariosContainer) {
            createOperariosContainer.dispatchEvent(new Event('change'));
        }

        document.getElementById('createSubOrderModal').style.display = 'block';
    }
    function closeCreateSubOrderModal() {
        document.getElementById('createSubOrderModal').style.display = 'none';
    }

    // Nueva función segura basada en búsqueda por ID dentro del array cargado
    function openEditSubOrderModalById(subOrderId) {
        const subOrder = currentSubOrders.find(s => s.id == subOrderId);
        if (!subOrder) return;

        document.getElementById('editSubOrderForm').action = '/admin/sub-orders/' + subOrder.id;
        document.getElementById('editSubOrderProceso').value = subOrder.proceso || '';
        document.getElementById('editSubOrderQuantity').value = subOrder.quantity || '';
        document.getElementById('editSubOrderCompleted').value = subOrder.completed_pieces || subOrder.completed_quantity || 0;

        const statusSelect = document.getElementById('editSubOrderStatus');
        if (statusSelect) statusSelect.value = subOrder.status || 'pending';

        const checkboxEnsamblaje = document.getElementById('editSubOrderEsEnsamblaje');
        if (checkboxEnsamblaje) checkboxEnsamblaje.checked = !!subOrder.es_ensamblaje;

        // Antes: el modal usaba un <select multiple id="editSubOrderOperarios">, y aquí
        // se marcaban sus <option> como selected. El modal ahora usa chips (checkboxes)
        // con IDs "editSubOrderOperario-{id}" dentro de #editSubOrderOperariosContainer.
        const operariosContainer = document.getElementById('editSubOrderOperariosContainer');
        if (operariosContainer) {
            // Desmarcar todos primero: el modal se reutiliza entre subórdenes distintas
            operariosContainer.querySelectorAll('input[type="checkbox"]').forEach(cb => cb.checked = false);

            (subOrder.operarios || []).forEach(op => {
                const operarioId = op.id || op;
                const checkbox = document.getElementById('editSubOrderOperario-' + operarioId);
                if (checkbox) checkbox.checked = true;
            });

            // Alpine no detecta por sí solo que los checkboxes cambiaron por JS: disparamos
            // "change" para que el contador "N seleccionados" del modal se actualice.
            operariosContainer.dispatchEvent(new Event('change'));
        }

        document.getElementById('editSubOrderModal').style.display = 'block';
    }

    function closeEditSubOrderModal() {
        document.getElementById('editSubOrderModal').style.display = 'none';
    }

    function openDeleteSubOrderModal(id, proceso) {
        document.getElementById('deleteSubOrderForm').action = '/admin/sub-orders/' + id;
        document.getElementById('deleteSubOrderName').textContent = proceso;
        document.getElementById('deleteSubOrderModal').style.display = 'block';
    }
    function closeDeleteSubOrderModal() {
        document.getElementById('deleteSubOrderModal').style.display = 'none';
    }

    // Cerrar con Escape
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            closeCreateModal();
            closeEditModal();
            closeDeleteModal();
            closeCreateSubOrderModal();
            closeEditSubOrderModal();
            closeDeleteSubOrderModal();
            closePanel();
        }
    });
</script>
@endpush