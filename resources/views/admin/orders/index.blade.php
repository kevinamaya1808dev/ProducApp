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
let currentOrder   = null;
let currentSubOrders = [];

// ── Helpers de modal ──────────────────────────────────────────────────────────
const $  = id  => document.getElementById(id);
const show = id => $(id).style.display = 'block';
const hide = id => $(id).style.display = 'none';

// ── Filtro de estado ──────────────────────────────────────────────────────────
let statusFilter = 'all';

function setStatusFilter(status, btn) {
    statusFilter = status;
    const OFF = ['bg-white','dark:bg-stone-900','border','border-slate-200','dark:border-stone-800','text-slate-600','dark:text-stone-300'];
    const ON  = ['bg-orange-600','text-white','shadow-sm','shadow-orange-600/20'];
    document.querySelectorAll('.status-filter-btn').forEach(b => { b.classList.remove(...ON); b.classList.add(...OFF); });
    btn.classList.remove(...OFF); btn.classList.add(...ON);
    filterOrders();
}

function filterOrders() {
    const q = $('searchInput').value.trim().toLowerCase();
    document.querySelectorAll('.order-row').forEach(r => {
        const matchSearch = ['orderNumber','productName','userName'].some(k => r.dataset[k]?.toLowerCase().includes(q));
        const matchStatus = statusFilter === 'all' || r.dataset.status === statusFilter;
        r.style.display = matchSearch && matchStatus ? '' : 'none';
    });
}

// ── Panel lateral ─────────────────────────────────────────────────────────────
function viewOrder(row) {
    currentOrder     = { ...row.dataset };
    currentSubOrders = JSON.parse(currentOrder.subOrders || '[]');

    const set = (id, val) => { const el = $(id); if (el) el.textContent = val; };
    set('panelOrderNumber', currentOrder.orderNumber);
    set('panelProduct',     currentOrder.productName);
    set('panelCategory',    currentOrder.category);
    set('panelProgress',    currentOrder.porcentaje + '%');
    set('panelProgressText', currentOrder.piezas + ' / ' + currentOrder.quantity + ' pzas');
    set('panelStatus',      currentOrder.statusLabel);
    set('panelPriority',    currentOrder.priorityLabel);
    set('panelOperator',    currentOrder.userName);
    set('panelStation',     currentOrder.estacion || 'Sin asignar');
    set('panelDeadline',    currentOrder.endDate   || 'Sin fecha');

    $('panelProgressBar').style.width = currentOrder.porcentaje + '%';

    const container = $('panelSubOrdersList');
    $('panelSubOrdersCount').textContent = currentSubOrders.length;
    container.innerHTML = currentSubOrders.length
        ? currentSubOrders.map(sub => {
            const ops = (sub.operarios || []).length
                ? sub.operarios.map(op => `
                    <div class="flex justify-between items-center text-[11px] text-slate-500 dark:text-stone-400 pl-2 border-l-2 border-orange-200 dark:border-orange-500/30 mt-1">
                        <span>${op.nombre} <span class="text-slate-400">· ${op.estacion || 'S/N'}</span></span>
                        <span class="font-semibold text-slate-600 dark:text-stone-300">${op.aportadas || 0} pzas</span>
                    </div>`).join('')
                : `<p class="text-[11px] text-slate-400 italic pl-2 mt-1">Sin operarios asignados</p>`;

            const piezas    = sub.completed_pieces ?? sub.completed_quantity ?? 0;
            const restantes = sub.quantity - piezas;
            const badges    = [
                sub.es_ensamblaje ? `<span class="text-[9px] font-bold uppercase bg-orange-100 text-orange-700 px-1.5 py-0.5 rounded ml-1.5">Ensamblaje</span>` : '',
                restantes > 0 && restantes <= 3 ? `<span class="text-[9px] font-bold uppercase bg-amber-100 text-amber-700 px-1.5 py-0.5 rounded ml-1.5">¡Casi listo!</span>` : '',
            ].join('');

            return `
                <div class="p-2.5 rounded-xl bg-slate-50 dark:bg-stone-800/60 border border-slate-100 dark:border-stone-800 text-xs">
                    <div class="flex justify-between items-center font-bold text-slate-800 dark:text-stone-200 mb-1">
                        <span>${sub.proceso} ${badges}</span>
                        <div class="flex items-center gap-2">
                            <span class="text-orange-600 dark:text-orange-400">${piezas}/${sub.quantity} pzas</span>
                            <button type="button" onclick="openEditSubOrderModalById(${sub.id})" class="text-slate-400 hover:text-orange-600 dark:hover:text-orange-400 p-0.5" title="Editar proceso">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 210.3H3v-3.572L16.732 3.732z"/></svg>
                            </button>
                            <button type="button" onclick="openDeleteSubOrderModal(${sub.id}, '${sub.proceso}')" class="text-slate-400 hover:text-red-600 p-0.5" title="Eliminar proceso">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                        </div>
                    </div>
                    ${ops}
                </div>`;
        }).join('')
        : `<p class="text-xs text-slate-400 dark:text-stone-500 italic mt-2">No hay procesos desglosados.</p>`;

    show('orderPanel');
}

function closePanel() { hide('orderPanel'); currentOrder = null; currentSubOrders = []; }

// ── Fila dinámica de suborden en formulario de creación ───────────────────────
function addSubOrderRow(containerId, data = null) {
    const container = $(containerId);
    const index     = container.children.length;
    const ops = `@foreach($operarios ?? [] as $op)<option value="{{ $op->id }}">{{ $op->name }}</option>@endforeach`;

    const row = Object.assign(document.createElement('div'), {
        className: 'grid grid-cols-12 gap-2 bg-slate-50 dark:bg-stone-800/50 p-2.5 rounded-xl border border-slate-200 dark:border-stone-800 items-start suborder-row mb-2',
        innerHTML: `
            <div class="col-span-4">
                <input type="text" name="sub_orders[${index}][proceso]" value="${data?.proceso || ''}" placeholder="Ej. Ensamblaje" required class="w-full text-xs bg-white dark:bg-stone-900 border border-slate-200 dark:border-stone-700 rounded-lg px-2.5 py-1.5 text-slate-800 dark:text-stone-200 outline-none focus:ring-2 focus:ring-orange-600/50">
                <label class="flex items-center gap-1.5 mt-1.5 text-[11px] text-slate-500 dark:text-stone-400 cursor-pointer select-none">
                    <input type="checkbox" name="sub_orders[${index}][es_ensamblaje]" value="1" ${data?.es_ensamblaje ? 'checked' : ''} class="rounded border-slate-300 text-orange-600 focus:ring-orange-500">
                    Fase final (ensamblaje)
                </label>
            </div>
            <div class="col-span-4">
                <select name="sub_orders[${index}][operarios][]" multiple size="3" class="w-full text-xs bg-white dark:bg-stone-900 border border-slate-200 dark:border-stone-700 rounded-lg px-2 py-1.5 text-slate-800 dark:text-stone-200 outline-none focus:ring-2 focus:ring-orange-600/50">${ops}</select>
                <p class="text-[10px] text-slate-400 mt-1">Ctrl/Cmd + clic para varios</p>
            </div>
            <div class="col-span-3">
                <input type="number" name="sub_orders[${index}][quantity]" value="${data?.quantity || ''}" placeholder="Cant." min="1" required class="w-full text-xs bg-white dark:bg-stone-900 border border-slate-200 dark:border-stone-700 rounded-lg px-2 py-1.5 text-slate-800 dark:text-stone-200 outline-none focus:ring-2 focus:ring-orange-600/50">
            </div>
            <div class="col-span-1 text-right">
                <button type="button" onclick="this.closest('.suborder-row').remove()" class="text-red-500 hover:text-red-700 font-bold text-sm bg-red-50 dark:bg-red-500/10 w-6 h-6 rounded-full flex items-center justify-center ml-auto">&times;</button>
            </div>`,
    });
    container.appendChild(row);

    if (data?.operarios?.length) {
        const sel = row.querySelector(`select[name="sub_orders[${index}][operarios][]"]`);
        data.operarios.forEach(op => { const o = sel.querySelector(`option[value="${op.id ?? op}"]`); if (o) o.selected = true; });
    }
}

// ── Modales de orden ──────────────────────────────────────────────────────────
function openCreateModal()  { $('createSubOrdersContainer')?.replaceChildren(); show('createOrderModal'); }
function closeCreateModal() { hide('createOrderModal'); window.dispatchEvent(new Event('closemodal')); }

function openEditModalFromPanel() {
    if (!currentOrder) return;
    const f = $('editOrderForm'); if (f) f.action = '/admin/orders/' + currentOrder.id;
    [['editOrderNumber', 'orderNumber'], ['editProductId', 'productId'], ['editQuantity', 'quantity'],
     ['editStatus', 'status'], ['editPriority', 'priority'], ['editUserId', 'userId'],
     ['editEstacion', 'estacion'], ['editStartDate', 'startDate'], ['editEndDate', 'endDate']
    ].forEach(([elId, key]) => { const el = $(elId); if (el) el.value = currentOrder[key] || ''; });
    show('editOrderModal');
}
function closeEditModal()  { hide('editOrderModal'); window.dispatchEvent(new Event('closemodal')); }

function openDeleteModalFromPanel() {
    if (!currentOrder) return;
    $('deleteOrderForm').action = '/admin/orders/' + currentOrder.id;
    $('deleteOrderNumber').textContent = currentOrder.orderNumber;
    show('deleteOrderModal');
}
function closeDeleteModal() { hide('deleteOrderModal'); }

// ── Modales de suborden ───────────────────────────────────────────────────────
function openCreateSubOrderModalFromPanel() {
    if (!currentOrder) return;
    $('createSubOrderOrderId').value = currentOrder.id;
    $('createSubOrderForm').reset();
    $('createSubOrderOperariosContainer')?.dispatchEvent(new Event('change'));
    show('createSubOrderModal');
}
function closeCreateSubOrderModal() { hide('createSubOrderModal'); }

function openEditSubOrderModalById(id) {
    const sub = currentSubOrders.find(s => s.id == id);
    if (!sub) return;

    $('editSubOrderForm').action          = '/admin/sub-orders/' + sub.id;
    $('editSubOrderProceso').value        = sub.proceso        || '';
    $('editSubOrderQuantity').value       = sub.quantity       || '';
    $('editSubOrderCompleted').value      = sub.completed_pieces ?? sub.completed_quantity ?? 0;
    const st = $('editSubOrderStatus');   if (st) st.value    = sub.status || 'pending';
    const cb = $('editSubOrderEsEnsamblaje'); if (cb) cb.checked = !!sub.es_ensamblaje;

    const opsCont = $('editSubOrderOperariosContainer');
    if (opsCont) {
        opsCont.querySelectorAll('input[type="checkbox"]').forEach(c => c.checked = false);
        (sub.operarios || []).forEach(op => {
            const c = document.getElementById('editSubOrderOperario-' + (op.id ?? op));
            if (c) c.checked = true;
        });
        opsCont.dispatchEvent(new Event('change'));
    }
    show('editSubOrderModal');
}
function closeEditSubOrderModal() { hide('editSubOrderModal'); }

function openDeleteSubOrderModal(id, proceso) {
    $('deleteSubOrderForm').action = '/admin/sub-orders/' + id;
    $('deleteSubOrderName').textContent = proceso;
    show('deleteSubOrderModal');
}
function closeDeleteSubOrderModal() { hide('deleteSubOrderModal'); }

// ── Escape global ─────────────────────────────────────────────────────────────
document.addEventListener('keydown', e => {
    if (e.key !== 'Escape') return;
    [closeCreateModal, closeEditModal, closeDeleteModal,
     closeCreateSubOrderModal, closeEditSubOrderModal, closeDeleteSubOrderModal, closePanel
    ].forEach(fn => fn());
});
</script>
@endpush