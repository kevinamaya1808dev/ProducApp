@extends('layouts.app')

@section('content')

<div class="p-6 max-w-[1600px] mx-auto overflow-hidden">

    @include('admin.users.components.header', ['totalUsers' => $totalUsers, 'users' =>$users])

    @include('admin.users.components.kpi-cards', ['totalUsers' => $totalUsers, 'users' =>$users])

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
    const authUserId = {{ auth()->id() }}; // ID del usuario autenticado en Laravel

    function filterUsers() {
        const query = document.getElementById('searchInput').value.trim().toLowerCase();
        const cards = document.querySelectorAll('.user-card');
        let visibleCount = 0;

        cards.forEach(card => {
            const name = card.dataset.name.toLowerCase();
            const email = card.dataset.email.toLowerCase();
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
        currentUser = {
            id: parseInt(card.dataset.id),
            name: card.dataset.name,
            email: card.dataset.email,
            roleId: card.dataset.roleId,
            roleName: card.dataset.roleName,
            initials: card.dataset.initials,
            puesto: card.dataset.puesto || '',
            turno: card.dataset.turno || '',
            estacion: card.dataset.estacion || '',
            meta_diaria: card.dataset.metaDiaria || '',
            active: card.dataset.active === '1',
            notas: card.dataset.notas || '',
            created: card.dataset.created,
            skills: JSON.parse(card.dataset.skills || '[]'),
            permissions: JSON.parse(card.dataset.permissions || '[]'),
            orders: JSON.parse(card.dataset.orders || '[]'),
            currentOrder: card.dataset.currentOrder || 'Ninguna'
        };

        // Rellenar información general del panel
        document.getElementById('panelInitials').textContent = currentUser.initials;
        document.getElementById('panelName').textContent = currentUser.name;
        document.getElementById('panelRole').textContent = currentUser.roleName;
        document.getElementById('panelEmail').textContent = currentUser.email;
        document.getElementById('panelTurno').textContent = currentUser.turno;
        document.getElementById('panelEstacion').textContent = currentUser.estacion;
        document.getElementById('panelAlta').textContent = currentUser.created;

        document.getElementById('panelOrdenes').textContent = currentUser.orders.length;
        document.getElementById('panelOrdenActual').textContent = currentUser.currentOrder;

       // Estado (Activo/Inactivo)
        const statusBadge = document.getElementById('panelStatus');
        const statusButtonText = document.getElementById('statusButtonText'); // <-- Referencia al texto del botón

        if(currentUser.active) {
            statusBadge.textContent = 'Activo';
            statusBadge.className = 'px-3 py-1 text-xs font-semibold rounded-full border border-emerald-200 dark:border-emerald-900/50 text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-950/50';
            
            if(statusButtonText) statusButtonText.textContent = 'Dar de baja'; // <-- Si está activo, el botón ofrece darlo de baja
        } else {
            statusBadge.textContent = 'Inactivo';
            statusBadge.className = 'px-3 py-1 text-xs font-semibold rounded-full border border-red-200 dark:border-red-900/50 text-red-600 dark:text-red-400 bg-red-50 dark:bg-red-950/50';
            
            if(statusButtonText) statusButtonText.textContent = 'Dar de alta'; // <-- Si está inactivo, el botón ofrece darlo de alta
        }

        // Renderizar Habilidades
        const skillsContainer = document.getElementById('panelSkillsContainer');
        const noSkillsMsg = document.getElementById('panelNoSkills');
        skillsContainer.innerHTML = '';

        if(currentUser.skills.length > 0) {
            noSkillsMsg.style.display = 'none';
            currentUser.skills.forEach(skill => {
                const skillName = typeof skill === 'object' ? (skill.skill || skill.name) : skill;
                const span = document.createElement('span');
                span.className = 'inline-block px-3 py-1 text-xs font-semibold text-orange-600 dark:text-orange-400 bg-orange-50/50 dark:bg-orange-950/50 border border-orange-200 dark:border-orange-900/50 rounded-full';
                span.textContent = skillName;
                skillsContainer.appendChild(span);
            });
        } else {
            noSkillsMsg.style.display = 'block';
        }

        // Lógica para mostrar u ocultar el formulario de eliminación del panel
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
            roles: [{ id: currentUser.roleId }],
            skills: currentUser.skills.map(s => typeof s === 'object' ? s : { skill: s }),
            permissions: currentUser.permissions.map(id => typeof id === 'object' ? id : { id: parseInt(id) })
        };

        openEditModal(formattedUser);
    }

    function closePanel() {
        document.getElementById('userPanel').style.display = 'none';
        currentUser = null;
    }

    function openCreateModal() {
        document.getElementById('createModal').style.display = 'block';
    }

    function closeCreateModal() {
        document.getElementById('createModal').style.display = 'none';
    }

    function openEditModal(user) {
        const form = document.getElementById('editForm');
        form.action = `/admin/users/${user.id}`;

        document.getElementById('editName').value = user.name || '';
        document.getElementById('editEmail').value = user.email || '';
        document.getElementById('editActive').value = user.active ? 1 : 0;
        document.getElementById('editPuesto').value = user.puesto || '';
        document.getElementById('editTurno').value = user.turno || '';
        document.getElementById('editEstacion').value = user.estacion || '';
        document.getElementById('editMetaDiaria').value = user.meta_diaria || '';
        document.getElementById('editNotas').value = user.notas || '';
        document.getElementById('editPassword').value = '';

        if (user.roles && user.roles.length > 0) {
            document.getElementById('editRoleId').value = user.roles[0].id;
        } else {
            document.getElementById('editRoleId').value = '';
        }

        const userSkillsNames = user.skills ? user.skills.map(s => s.skill || s.name || s) : [];
        document.querySelectorAll('.edit-skill-checkbox').forEach(cb => {
            cb.checked = userSkillsNames.includes(cb.value);
        });

        const userPermissionIds = user.permissions ? user.permissions.map(p => typeof p === 'object' ? p.id : parseInt(p)) : [];
        document.querySelectorAll('.permission-checkbox').forEach(cb => {
            cb.checked = userPermissionIds.includes(parseInt(cb.value));
        });

        document.getElementById('editModal').style.display = 'block';
    }

    function closeEditModal() {
        document.getElementById('editModal').style.display = 'none';
    }

    // --- MODIFICACIÓN DINÁMICA PARA ALTA / BAJA ---
    function toggleStatusFromPanel() {
        if(!currentUser) return;

        const modalTitle = document.getElementById('deactivateModalTitle');
        const modalMessage = document.getElementById('deactivateModalMessage');
        const modalIconContainer = document.getElementById('deactivateModalIconContainer');
        const modalIcon = document.getElementById('deactivateModalIcon');
        const modalConfirmBtn = document.getElementById('deactivateModalConfirmBtn');

        if (currentUser.active) {
            // Estilos y textos para: DAR DE BAJA
            modalTitle.textContent = "Dar de baja";
            modalMessage.innerHTML = `¿Dar de baja a "<span class="text-slate-800 dark:text-stone-200 font-medium">${currentUser.name}</span>"? No podrá iniciar sesión ni recibir órdenes.`;
            
            modalIconContainer.className = "flex items-center justify-center w-12 h-12 mb-4 bg-red-100 dark:bg-red-950/50 rounded-full";
            modalIcon.className = "w-6 h-6 text-red-500 dark:text-red-400";
            modalIcon.innerHTML = `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>`;
            
            modalConfirmBtn.className = "w-full px-4 py-2.5 text-sm font-semibold text-white bg-red-600 hover:bg-red-700 rounded-xl transition-colors shadow-lg shadow-red-600/20";
            modalConfirmBtn.textContent = "Dar de baja";
        } else {
            // Estilos y textos para: DAR DE ALTA
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
        if(!currentUser) return;
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