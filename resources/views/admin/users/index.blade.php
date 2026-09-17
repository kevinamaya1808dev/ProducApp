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

    @can('manage-users')
        @include('admin.users.modals.create')
        @include('admin.users.modals.edit')
        @include('admin.users.modals.delete')
        @include('admin.users.modals.deactivate')
    @endcan

</div>

@endsection

@push('scripts')
<script>
    let currentUser = null;
    const authUserId = {{ auth()->id() }};

    function filterUsers() {
        const query = document.getElementById('searchInput').value.trim().toLowerCase();
        const cards = document.querySelectorAll('.user-card');
        let visibleCount = 0;

        cards.forEach(card => {
            const name = card.dataset.name ? card.dataset.name.toLowerCase() : '';
            const email = card.dataset.email ? card.dataset.email.toLowerCase() : '';
            const matches = name.includes(query) || email.includes(query);
            card.style.display = matches ? '' : 'none';
            if (matches) visibleCount++;
        });

        const noResults = document.getElementById('noResults');
        if (noResults) {
            noResults.style.display = visibleCount === 0 ? '' : 'none';
        }
    }

    function selectUser(card) {
        let permissionsParsed = [];
        let ordersParsed = [];

        try { permissionsParsed = JSON.parse(card.dataset.permissions || '[]'); } catch(e) { permissionsParsed = []; }
        try { ordersParsed = JSON.parse(card.dataset.orders || '[]'); } catch(e) { ordersParsed = []; }

        currentUser = {
            id: parseInt(card.dataset.id),
            name: card.dataset.name || '',
            email: card.dataset.email || '',
            roleId: card.dataset.roleId || '',
            roleName: card.dataset.roleName || 'Sin rol',
            initials: card.dataset.initials || '',
            puesto: card.dataset.puesto || '',
            turno: card.dataset.turno || '',
            estacion: card.dataset.estacion || '',
            meta_diaria: card.dataset.metaDiaria || '',
            active: card.dataset.active === '1' || card.dataset.active === 'true',
            notas: card.dataset.notas || '',
            created: card.dataset.created || '',
            permissions: permissionsParsed,
            orders: ordersParsed,
            currentOrder: card.dataset.currentOrder || 'Ninguna'
        };

        // Rellenar información general del panel
        document.getElementById('panelInitials').textContent = currentUser.initials;
        document.getElementById('panelName').textContent = currentUser.name;
        document.getElementById('panelRole').textContent = currentUser.roleName;
        document.getElementById('panelEmail').textContent = currentUser.email;
        document.getElementById('panelTurno').textContent = currentUser.turno || 'Sin asignar';
        document.getElementById('panelEstacion').textContent = currentUser.estacion || 'Sin asignar';
        document.getElementById('panelAlta').textContent = currentUser.created;

        document.getElementById('panelOrdenes').textContent = currentUser.orders.length;
        document.getElementById('panelOrdenActual').textContent = currentUser.currentOrder;

        // Estado (Activo/Inactivo)
        const statusBadge = document.getElementById('panelStatus');
        const statusButtonText = document.getElementById('statusButtonText');

        if (currentUser.active) {
            statusBadge.textContent = 'Activo';
            statusBadge.className = 'px-3 py-1 text-xs font-semibold rounded-full border border-emerald-200 dark:border-emerald-900/50 text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-950/50';
            if (statusButtonText) statusButtonText.textContent = 'Dar de baja';
        } else {
            statusBadge.textContent = 'Inactivo';
            statusBadge.className = 'px-3 py-1 text-xs font-semibold rounded-full border border-red-200 dark:border-red-900/50 text-red-600 dark:text-red-400 bg-red-50 dark:bg-red-950/50';
            if (statusButtonText) statusButtonText.textContent = 'Dar de alta';
        }

        // Formulario de eliminación
        const deleteForm = document.getElementById('deleteUserForm');
        if (deleteForm) {
            if (currentUser.id === 1 || currentUser.id === authUserId) {
                deleteForm.style.display = 'none';
            } else {
                deleteForm.style.display = 'block';
                deleteForm.action = `/admin/users/${currentUser.id}`;
            }
        }

        document.getElementById('userPanel').style.display = 'flex';
    }

    function triggerEditModal() {
        if (!currentUser) return;

        const formattedUser = {
            id: currentUser.id,
            name: currentUser.name,
            email: currentUser.email,
            active: currentUser.active,
            puesto: currentUser.puesto,
            turno: currentUser.turno,
            estacion: currentUser.estacion,
            meta_diaria: currentUser.meta_diaria,
            notas: currentUser.notas,
            roles: currentUser.roleId ? [{ id: currentUser.roleId }] : [],
            permissions: currentUser.permissions.map(id => typeof id === 'object' ? id : { id: parseInt(id) })
        };

        openEditModal(formattedUser);
    }

    function openEditModal(user) {
        if (!user) return;

        const form = document.getElementById('editForm');
        if (form && user.id) form.action = `/admin/users/${user.id}`;

        const setVal = (id, val) => {
            const el = document.getElementById(id);
            if (el) el.value = (val !== null && val !== undefined) ? val : '';
        };

        setVal('editName', user.name);
        setVal('editEmail', user.email);
        setVal('editActive', user.active ? 1 : 0);
        setVal('editPuesto', user.puesto);
        setVal('editTurno', user.turno);
        setVal('editEstacion', user.estacion);
        setVal('editMetaDiaria', user.meta_diaria);
        setVal('editNotas', user.notas);
        setVal('editPassword', '');

        // Rol: actualiza el estado de Alpine directamente (el dropdown de Rol
        // usa x-data/x-model, así que escribir el <input type="hidden"> con
        // JS vanilla no se reflejaba ni en Alpine ni en el submit real).
        const roleDropdown = document.getElementById('editRoleDropdown');
        if (roleDropdown && window.Alpine) {
            const data = Alpine.$data(roleDropdown);
            const roleId = (user.roles && user.roles.length > 0)
                ? String(user.roles[0].id || user.roles[0])
                : (user.roleId ? String(user.roleId) : '');

            data.selectedId = roleId;
            data.selectedName = data.options[roleId] || 'Selecciona un rol';
        }

        const userPermissionIds = user.permissions ? user.permissions.map(p => typeof p === 'object' ? p.id : parseInt(p)) : [];
        document.querySelectorAll('.permission-checkbox').forEach(cb => {
            cb.checked = userPermissionIds.includes(parseInt(cb.value));
        });

        const editModal = document.getElementById('editModal');
        if (editModal) editModal.style.display = 'block';
    }

    function closeEditModal() {
        const editModal = document.getElementById('editModal');
        if (editModal) editModal.style.display = 'none';
    }

    function closePanel() {
        document.getElementById('userPanel').style.display = 'none';
        currentUser = null;
    }

    function openCreateModal() {
        const createModal = document.getElementById('createModal');
        if (createModal) createModal.style.display = 'block';
    }

    function closeCreateModal() {
        const createModal = document.getElementById('createModal');
        if (createModal) createModal.style.display = 'none';
    }

    function toggleStatusFromPanel() {
        if (!currentUser) return;

        const modalTitle = document.getElementById('deactivateModalTitle');
        const modalMessage = document.getElementById('deactivateModalMessage');
        const modalIconContainer = document.getElementById('deactivateModalIconContainer');
        const modalIcon = document.getElementById('deactivateModalIcon');
        const modalConfirmBtn = document.getElementById('deactivateModalConfirmBtn');

        if (currentUser.active) {
            modalTitle.textContent = "Dar de baja";
            modalMessage.innerHTML = `¿Dar de baja a "<span class="text-slate-800 dark:text-stone-200 font-medium">${currentUser.name}</span>"? No podrá iniciar sesión ni recibir órdenes.`;

            modalIconContainer.className = "flex items-center justify-center w-12 h-12 mb-4 bg-red-100 dark:bg-red-950/50 rounded-full";
            modalIcon.className = "w-6 h-6 text-red-500 dark:text-red-400";
            modalIcon.innerHTML = `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>`;

            modalConfirmBtn.className = "w-full px-4 py-2.5 text-sm font-semibold text-white bg-red-600 hover:bg-red-700 rounded-xl transition-colors shadow-lg shadow-red-600/20";
            modalConfirmBtn.textContent = "Dar de baja";
        } else {
            modalTitle.textContent = "Dar de alta";
            modalMessage.innerHTML = `¿Dar de alta a "<span class="text-slate-800 dark:text-stone-200 font-medium">${currentUser.name}</span>"? Podrá volver a ingresar al sistema.`;

            modalIconContainer.className = "flex items-center justify-center w-12 h-12 mb-4 bg-emerald-100 dark:bg-emerald-950/50 rounded-full";
            modalIcon.className = "w-6 h-6 text-emerald-500 dark:text-emerald-400";
            modalIcon.innerHTML = `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>`;

            modalConfirmBtn.className = "w-full px-4 py-2.5 text-sm font-semibold text-white bg-emerald-600 hover:bg-emerald-700 rounded-xl transition-colors shadow-lg shadow-emerald-600/20";
            modalConfirmBtn.textContent = "Dar de alta";
        }

        document.getElementById('deactivateModal').style.display = 'block';
    }

    function closeDeactivateModal() {
        document.getElementById('deactivateModal').style.display = 'none';
    }

    function confirmDeactivate() {
        if (!currentUser) return;
        const form = document.getElementById('statusFormPanel');
        if (form) {
            form.action = '/admin/users/' + currentUser.id;
            document.getElementById('statusFormName').value = currentUser.name;
            document.getElementById('statusFormEmail').value = currentUser.email;
            document.getElementById('statusFormRole').value = currentUser.roleId;
            document.getElementById('statusFormActive').value = currentUser.active ? '0' : '1';
            form.submit();
        }
    }

    function openDeleteModal() {
        if (!currentUser) return;
        if (currentUser.id === 1 || currentUser.id === authUserId) return;

        document.getElementById('deleteUserName').textContent = currentUser.name;
        document.getElementById('deleteForm').action = `/admin/users/${currentUser.id}`;
        document.getElementById('deleteModal').style.display = 'block';
    }

    function closeDeleteModal() {
        document.getElementById('deleteModal').style.display = 'none';
    }
</script>
@endpush