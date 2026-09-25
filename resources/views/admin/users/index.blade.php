@extends('layouts.app')

@section('content')

<div class="p-6 max-w-[1600px] mx-auto overflow-hidden">

    @include('admin.users.components.header', ['totalUsers' => $totalUsers, 'users' => $users])

    @include('admin.users.components.kpi-cards', ['totalUsers' => $totalUsers, 'users' => $users])

    @include('admin.users.components.search-bar')

    <!-- Grid + Panel -->
    <div class="flex items-start gap-6 relative">
        @include('admin.users.components.users-grid', ['users' => $users])
        @include('admin.users.components.detail-panel')
    </div>

   @can('users.create')
    @include('admin.users.modals.create')
@endcan
        @can('users.edit')
    @include('admin.users.modals.edit')
    @include('admin.users.modals.deactivate')
@endcan
        @can('users.delete')
    @include('admin.users.modals.delete')
@endcan

</div>

@endsection

@push('scripts')
<script>
let currentUser = null;
const AUTH_ID   = {{ auth()->id() }};
const $         = id => document.getElementById(id);

// ── Filtro de búsqueda ────────────────────────────────────────────────────────
function filterUsers() {
    const q = $('searchInput').value.trim().toLowerCase();
    let visible = 0;
    document.querySelectorAll('.user-card').forEach(c => {
        const match = [c.dataset.name, c.dataset.email].some(v => v?.toLowerCase().includes(q));
        c.style.display = match ? '' : 'none';
        if (match) visible++;
    });
    const nr = $('noResults');
    if (nr) nr.style.display = visible ? 'none' : '';
}

// ── Parseo seguro de JSON ─────────────────────────────────────────────────────
const safeJSON = (str, fallback = []) => { try { return JSON.parse(str || '[]'); } catch { return fallback; } };

// ── Panel lateral ─────────────────────────────────────────────────────────────
function selectUser(card) {
    currentUser = {
        id:           parseInt(card.dataset.id),
        name:         card.dataset.name         || '',
        email:        card.dataset.email        || '',
        roleId:       card.dataset.roleId       || '',
        roleName:     card.dataset.roleName     || 'Sin rol',
        initials:     card.dataset.initials     || '',
        puesto:       card.dataset.puesto       || '',
        turno:        card.dataset.turno        || '',
        estacion:     card.dataset.estacion     || '',
        meta_diaria:  card.dataset.metaDiaria   || '',
        active:       card.dataset.active === '1' || card.dataset.active === 'true',
        notas:        card.dataset.notas        || '',
        created:      card.dataset.created      || '',
        currentOrder: card.dataset.currentOrder || 'Ninguna',
        permissions:  safeJSON(card.dataset.permissions),
        orders:       safeJSON(card.dataset.orders),
    };

    const set = (id, val) => { const el = $(id); if (el) el.textContent = val; };
    set('panelInitials',    currentUser.initials);
    set('panelName',        currentUser.name);
    set('panelRole',        currentUser.roleName);
    set('panelEmail',       currentUser.email);
    set('panelTurno',       currentUser.turno    || 'Sin asignar');
    set('panelEstacion',    currentUser.estacion  || 'Sin asignar');
    set('panelAlta',        currentUser.created);
    set('panelOrdenes',     currentUser.orders.length);
    set('panelOrdenActual', currentUser.currentOrder);

    const badge = $('panelStatus');
    const btnTxt = $('statusButtonText');
    if (currentUser.active) {
        badge.textContent  = 'Activo';
        badge.className    = 'px-3 py-1 text-xs font-semibold rounded-full border border-emerald-200 dark:border-emerald-900/50 text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-950/50';
        if (btnTxt) btnTxt.textContent = 'Dar de baja';
    } else {
        badge.textContent  = 'Inactivo';
        badge.className    = 'px-3 py-1 text-xs font-semibold rounded-full border border-red-200 dark:border-red-900/50 text-red-600 dark:text-red-400 bg-red-50 dark:bg-red-950/50';
        if (btnTxt) btnTxt.textContent = 'Dar de alta';
    }

    const delForm = $('deleteUserForm');
    if (delForm) {
        const protegido = currentUser.id === 1 || currentUser.id === AUTH_ID;
        delForm.style.display = protegido ? 'none' : 'block';
        if (!protegido) delForm.action = `/admin/users/${currentUser.id}`;
    }

    $('userPanel').style.display = 'flex';
}

function closePanel() { $('userPanel').style.display = 'none'; currentUser = null; }

// ── Modal editar ──────────────────────────────────────────────────────────────
function triggerEditModal() { if (currentUser) openEditModal({ ...currentUser, roles: currentUser.roleId ? [{ id: currentUser.roleId }] : [] }); }

function openEditModal(user) {
    if (!user) return;
    const form = $('editForm');
    if (form && user.id) form.action = `/admin/users/${user.id}`;

    const setVal = (id, v) => { const el = $(id); if (el) el.value = v ?? ''; };
    setVal('editName',       user.name);
    setVal('editEmail',      user.email);
    setVal('editActive',     user.active ? 1 : 0);
    setVal('editPuesto',     user.puesto);
    setVal('editTurno',      user.turno);
    setVal('editEstacion',   user.estacion);
    setVal('editMetaDiaria', user.meta_diaria);
    setVal('editNotas',      user.notas);
    setVal('editPassword',   '');

    try {
        const drop = $('editRoleDropdown');
        if (drop && window.Alpine) {
            const data   = Alpine.$data(drop);
            const roleId = String(user.roles?.[0]?.id ?? user.roles?.[0] ?? user.roleId ?? '');
            data.selectedId   = roleId;
            data.selectedName = data.options[roleId] || 'Selecciona un rol';
        }
    } catch (e) { console.error('Alpine role dropdown:', e); }

    const permBtn = $('editPermissionsBtn');
    if (permBtn && user.id) permBtn.href = `/admin/users/${user.id}/permissions`;

    $('editModal').style.display = 'block';
}
function closeEditModal()  { $('editModal').style.display = 'none'; }

// ── Modal crear ───────────────────────────────────────────────────────────────
function openCreateModal()  { $('createModal').style.display = 'block'; }
function closeCreateModal() { $('createModal').style.display = 'none'; }

// ── Modal dar de baja / alta ──────────────────────────────────────────────────
const STATUS_CFG = {
    baja: {
        title: 'Dar de baja',
        msg:   name => `¿Dar de baja a "<span class="text-slate-800 dark:text-stone-200 font-medium">${name}</span>"? No podrá iniciar sesión ni recibir órdenes.`,
        icon:  'bg-red-100 dark:bg-red-950/50', iconColor: 'text-red-500 dark:text-red-400',
        path:  'M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16',
        btn:   'bg-red-600 hover:bg-red-700 shadow-red-600/20', btnLabel: 'Dar de baja',
    },
    alta: {
        title: 'Dar de alta',
        msg:   name => `¿Dar de alta a "<span class="text-slate-800 dark:text-stone-200 font-medium">${name}</span>"? Podrá volver a ingresar al sistema.`,
        icon:  'bg-emerald-100 dark:bg-emerald-950/50', iconColor: 'text-emerald-500 dark:text-emerald-400',
        path:  'M5 13l4 4L19 7',
        btn:   'bg-emerald-600 hover:bg-emerald-700 shadow-emerald-600/20', btnLabel: 'Dar de alta',
    },
};

function toggleStatusFromPanel() {
    if (!currentUser) return;
    const cfg = STATUS_CFG[currentUser.active ? 'baja' : 'alta'];

    $('deactivateModalTitle').textContent   = cfg.title;
    $('deactivateModalMessage').innerHTML   = cfg.msg(currentUser.name);
    $('deactivateModalIconContainer').className = `flex items-center justify-center w-12 h-12 mb-4 ${cfg.icon} rounded-full`;
    const icon = $('deactivateModalIcon');
    icon.className = `w-6 h-6 ${cfg.iconColor}`;
    icon.innerHTML = `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="${cfg.path}"/>`;
    $('deactivateModalConfirmBtn').className = `w-full px-4 py-2.5 text-sm font-semibold text-white ${cfg.btn} rounded-xl transition-colors shadow-lg`;
    $('deactivateModalConfirmBtn').textContent = cfg.btnLabel;

    $('deactivateModal').style.display = 'block';
}
function closeDeactivateModal() { $('deactivateModal').style.display = 'none'; }

function confirmDeactivate() {
    if (!currentUser) return;
    const form = $('statusFormPanel');
    if (!form) return;
    form.action = '/admin/users/' + currentUser.id;
    $('statusFormName').value   = currentUser.name;
    $('statusFormEmail').value  = currentUser.email;
    $('statusFormRole').value   = currentUser.roleId;
    $('statusFormActive').value = currentUser.active ? '0' : '1';
    form.submit();
}

// ── Modal eliminar ────────────────────────────────────────────────────────────
function openDeleteModal() {
    if (!currentUser || currentUser.id === 1 || currentUser.id === AUTH_ID) return;
    $('deleteUserName').textContent   = currentUser.name;
    $('deleteForm').action            = `/admin/users/${currentUser.id}`;
    $('deleteModal').style.display    = 'block';
}
function closeDeleteModal() { $('deleteModal').style.display = 'none'; }
</script>
@endpush