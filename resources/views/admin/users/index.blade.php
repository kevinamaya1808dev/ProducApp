@extends('layouts.app')

@section('content')

<div class="p-6 max-w-[1600px] mx-auto overflow-hidden">

    @include('admin.users.components.flash-messages')

    @include('admin.users.components.header', ['totalUsers' => $totalUsers, 'users' => $users])

    @include('admin.users.components.kpi-cards', ['totalUsers' => $totalUsers, 'users' => $users])

    @include('admin.users.components.search-bar')

    <!-- Grid + Panel -->
    <div class="flex items-start gap-6 relative">
        @include('admin.users.components.users-grid', ['users' => $users])
        @include('admin.users.components.detail-panel')
    </div>

    @can('manage-operators')
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
            id: card.dataset.id,
            name: card.dataset.name,
            email: card.dataset.email,
            roleId: card.dataset.roleId,
            roleName: card.dataset.roleName,
            initials: card.dataset.initials,
            turno: card.dataset.turno,
            estacion: card.dataset.estacion,
            active: card.dataset.active === '1',
            notas: card.dataset.notas,
            created: card.dataset.created,
            skills: JSON.parse(card.dataset.skills || '[]'),
            permissions: JSON.parse(card.dataset.permissions || '[]'),
            orders: JSON.parse(card.dataset.orders || '[]'),
            currentOrder: card.dataset.currentOrder || 'Ninguna'
        };

        document.getElementById('panelInitials').textContent = currentUser.initials;
        document.getElementById('panelName').textContent = currentUser.name;
        document.getElementById('panelRole').textContent = currentUser.roleName;
        document.getElementById('panelEmail').textContent = currentUser.email;
        document.getElementById('panelTurno').textContent = currentUser.turno;
        document.getElementById('panelEstacion').textContent = currentUser.estacion;
        document.getElementById('panelAlta').textContent = currentUser.created;

        document.getElementById('panelOrdenes').textContent = currentUser.orders.length;
        document.getElementById('panelOrdenActual').textContent = currentUser.currentOrder;

        const statusBadge = document.getElementById('panelStatus');
        if(currentUser.active) {
            statusBadge.textContent = 'Activo';
            statusBadge.className = 'px-3 py-1 text-xs font-semibold rounded-full border border-emerald-200 text-emerald-600 bg-emerald-50';
        } else {
            statusBadge.textContent = 'Inactivo';
            statusBadge.className = 'px-3 py-1 text-xs font-semibold rounded-full border border-red-200 text-red-600 bg-red-50';
        }

        const skillsContainer = document.getElementById('panelSkillsContainer');
        const noSkillsMsg = document.getElementById('panelNoSkills');
        skillsContainer.innerHTML = '';

        if(currentUser.skills.length > 0) {
            noSkillsMsg.style.display = 'none';
            currentUser.skills.forEach(skill => {
                const span = document.createElement('span');
                span.className = 'inline-block px-3 py-1 text-xs font-semibold text-orange-600 bg-orange-50/50 border border-orange-200 rounded-full';
                span.textContent = skill;
                skillsContainer.appendChild(span);
            });
        } else {
            noSkillsMsg.style.display = 'block';
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
            turno: currentUser.turno,
            planta: currentUser.estacion,
            notas: currentUser.notas,
            roles: [{ id: currentUser.roleId }],
            skills: currentUser.skills.map(s => ({ skill: s })),
            permissions: currentUser.permissions.map(id => ({ id: id }))
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
        document.getElementById('editRoleId').value = (user.roles && user.roles.length > 0) ? user.roles[0].id : '';
        document.getElementById('editActive').value = user.active ? 1 : 0;
        document.getElementById('editTurno').value = user.turno || '';
        document.getElementById('editPlanta').value = user.planta || '';
        document.getElementById('editNotas').value = user.notas || '';
        document.getElementById('editPassword').value = '';

        const userSkills = user.skills ? user.skills.map(s => s.skill) : [];
        document.querySelectorAll('.edit-skill-checkbox').forEach(checkbox => {
            checkbox.checked = userSkills.includes(checkbox.value);
        });

        const userPermissions = user.permissions ? user.permissions.map(p => p.id) : [];
        document.querySelectorAll('.permission-checkbox').forEach(checkbox => {
            checkbox.checked = userPermissions.includes(parseInt(checkbox.value));
        });

        document.getElementById('editModal').style.display = 'block';
    }

    function closeEditModal() {
        document.getElementById('editModal').style.display = 'none';
    }

    function toggleStatusFromPanel() {
        if(!currentUser) return;
        document.getElementById('deactivateUserName').textContent = currentUser.name;
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
</script>
@endpush