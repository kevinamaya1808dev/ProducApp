@extends('layouts.app')

@section('content')

<div class="p-6 max-w-[1600px] mx-auto overflow-hidden">

    {{-- Alertas de sesión --}}
    @if(session('success'))
        <div class="mb-4 bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm font-medium px-4 py-3 rounded-lg">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="mb-4 bg-red-50 border border-red-200 text-red-700 text-sm font-medium px-4 py-3 rounded-lg">
            {{ session('error') }}
        </div>
    @endif

    <!-- Encabezado -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Operarios</h1>
            <p class="text-sm text-slate-500 mt-1">Personal de producción &middot; {{ $totalUsers }} registro(s) &middot; {{ $users->count() }} gestionable(s)</p>
        </div>

        @can('manage-operators')
        <button type="button" onclick="openCreateModal()" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-lg text-sm font-semibold transition-all shadow-sm shadow-blue-600/30 flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Agregar Operario
        </button>
        @endcan
    </div>

    <!-- KPI Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-white border border-slate-200 rounded-xl p-5 shadow-sm">
            <p class="text-xs font-bold text-slate-500 tracking-wider uppercase mb-1">Total Usuarios</p>
            <p class="text-3xl font-black text-slate-900">{{ $totalUsers }}</p>
        </div>
        <div class="bg-white border border-slate-200 rounded-xl p-5 shadow-sm">
            <p class="text-xs font-bold text-slate-500 tracking-wider uppercase mb-1">Administradores</p>
            <p class="text-3xl font-black text-blue-600">{{ $users->filter(fn($u) => $u->roles->contains('slug', 'admin'))->count() }}</p>
        </div>
        <div class="bg-white border border-slate-200 rounded-xl p-5 shadow-sm">
            <p class="text-xs font-bold text-slate-500 tracking-wider uppercase mb-1">Operarios</p>
            <p class="text-3xl font-black text-emerald-600">{{ $users->filter(fn($u) => $u->roles->contains('slug', 'operario'))->count() }}</p>
        </div>
        <div class="bg-white border border-slate-200 rounded-xl p-5 shadow-sm">
            <p class="text-xs font-bold text-slate-500 tracking-wider uppercase mb-1">Becarios</p>
            <p class="text-3xl font-black text-amber-500">{{ $users->filter(fn($u) => $u->roles->contains('slug', 'becario'))->count() }}</p>
        </div>
    </div>

    <!-- Barra de Búsqueda -->
    <div class="flex flex-col lg:flex-row justify-between gap-4 mb-6 items-start lg:items-center">
        <div class="bg-white p-2.5 rounded-xl border border-slate-200 shadow-sm relative w-full sm:w-80">
            <svg class="w-4 h-4 text-slate-400 absolute left-4 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            <input type="text" id="searchInput" oninput="filterUsers()" placeholder="Buscar por nombre o correo..." class="w-full pl-8 pr-2 text-sm bg-transparent border-none focus:ring-0 text-slate-700 placeholder-slate-400 outline-none">
        </div>
    </div>

    <!-- Contenedor Principal -->
    <div class="flex items-start gap-6 relative">

        <!-- Grid de Tarjetas -->
        <div id="usersGrid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 flex-1 transition-all duration-300">
            @forelse($users as $user)
                @php
                    $role = $user->roles->first();
                    $initials = collect(explode(' ', $user->name))->map(fn($n) => strtoupper(substr($n, 0, 1)))->take(2)->implode('');
                @endphp
                <div
                    class="user-card bg-white border border-slate-200 rounded-2xl p-5 shadow-sm hover:border-blue-300 hover:shadow-md transition-all cursor-pointer relative overflow-hidden group"
                    data-id="{{ $user->id }}"
                    data-name="{{ $user->name }}"
                    data-email="{{ $user->email }}"
                    data-role-id="{{ $role?->id }}"
                    data-role-name="{{ $role?->name ?? 'Sin rol' }}"
                    data-initials="{{ $initials }}"
                    data-permissions="{{ $user->permissions->pluck('id')->implode(',') }}"
                    onclick="selectUser(this)"
                >
                    <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-blue-500 to-indigo-500"></div>
                    <div class="flex justify-between items-start mb-4">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 rounded-full bg-blue-600 text-white font-bold flex items-center justify-center text-sm shadow-sm">{{ $initials }}</div>
                            <div>
                                <h3 class="font-bold text-slate-900 text-base group-hover:text-blue-600 transition-colors">{{ $user->name }}</h3>
                                <span class="inline-block bg-blue-50 text-blue-700 border border-blue-100 text-[10px] font-bold px-2 py-0.5 rounded-md mt-0.5">{{ $role?->name ?? 'Sin rol' }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="pt-3 border-t border-slate-100 text-xs">
                        <p class="text-slate-400 font-medium uppercase tracking-wider text-[10px]">Correo</p>
                        <p class="font-bold text-slate-800 mt-0.5 truncate">{{ $user->email }}</p>
                    </div>
                </div>
            @empty
                <p id="emptyState" class="text-sm text-slate-400 col-span-full text-center py-10">No hay operarios gestionables todavía.</p>
            @endforelse
            <p id="noResults" class="text-sm text-slate-400 col-span-full text-center py-10" style="display:none;">No se encontraron usuarios.</p>
        </div>

        <!-- Panel Lateral -->
        <div id="userPanel" class="w-full max-w-[360px] shrink-0 bg-white border border-slate-200 rounded-2xl shadow-sm hidden lg:flex flex-col relative" style="display:none;">

            <div class="p-5 border-b border-slate-100 flex justify-between items-center bg-slate-50/50 rounded-t-2xl">
                <h3 class="font-bold text-slate-900 text-sm">Perfil del Usuario</h3>
                <button type="button" onclick="closePanel()" class="text-slate-400 hover:text-slate-600">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <div class="p-5 flex-1 overflow-y-auto">
                <div class="text-center mb-6">
                    <div id="panelInitials" class="w-16 h-16 rounded-full bg-blue-600 text-white font-black text-xl flex items-center justify-center mx-auto mb-3 shadow-md"></div>
                    <h2 id="panelName" class="text-lg font-black text-slate-900"></h2>
                    <span id="panelRole" class="inline-block bg-blue-50 text-blue-700 border border-blue-100 text-[11px] font-bold px-2.5 py-0.5 rounded-md mt-1"></span>
                </div>

                <ul class="space-y-3.5 text-sm mb-6">
                    <li class="flex justify-between items-center border-b border-slate-50 pb-2.5">
                        <span class="text-slate-400 font-semibold text-xs uppercase tracking-wider">Correo</span>
                        <span id="panelEmail" class="text-slate-800 font-bold truncate max-w-[180px]"></span>
                    </li>
                </ul>
            </div>

            <div class="p-4 border-t border-slate-100 bg-slate-50/50 rounded-b-2xl flex gap-2">
                @can('manage-operators')
                <button type="button" onclick="openEditModalFromPanel()" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-semibold text-sm py-2.5 rounded-lg transition-colors shadow-sm flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                    Editar
                </button>
                <button type="button" onclick="openDeleteModalFromPanel()" class="bg-red-50 hover:bg-red-100 text-red-600 font-semibold text-sm py-2.5 px-4 rounded-lg transition-colors shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                </button>
                @else
                <div class="text-center text-xs text-slate-400 py-1 w-full">
                    Modo visualización (Sin privilegios de edición)
                </div>
                @endcan
            </div>
        </div>
    </div>

    @can('manage-operators')
        @include('admin.users.modals.create')
        @include('admin.users.modals.edit')
        @include('admin.users.modals.delete')
    @endcan

</div>

@endsection

@push('scripts')
<script>
    let currentUser = null;

    // ===== Búsqueda / filtro =====
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

        document.getElementById('noResults').style.display = visibleCount === 0 ? '' : 'none';
    }

    // ===== Panel lateral =====
    function selectUser(card) {
        currentUser = {
            id: card.dataset.id,
            name: card.dataset.name,
            email: card.dataset.email,
            roleId: card.dataset.roleId,
            roleName: card.dataset.roleName,
            initials: card.dataset.initials,
            permissionIds: card.dataset.permissions ? card.dataset.permissions.split(',') : [],
        };

        document.getElementById('panelInitials').textContent = currentUser.initials;
        document.getElementById('panelName').textContent = currentUser.name;
        document.getElementById('panelRole').textContent = currentUser.roleName;
        document.getElementById('panelEmail').textContent = currentUser.email;

        document.getElementById('userPanel').style.display = 'flex';
    }

    function closePanel() {
        document.getElementById('userPanel').style.display = 'none';
        currentUser = null;
    }

    // ===== Modal: Crear =====
    function openCreateModal() {
        document.getElementById('createModal').style.display = 'block';
    }
    function closeCreateModal() {
        document.getElementById('createModal').style.display = 'none';
    }

    // ===== Modal: Editar =====
    function openEditModalFromPanel() {
        if (!currentUser) return;
        document.getElementById('editForm').action = '/admin/users/' + currentUser.id;
        document.getElementById('editName').value = currentUser.name;
        document.getElementById('editEmail').value = currentUser.email;
        document.getElementById('editPassword').value = '';
        document.getElementById('editRoleId').value = currentUser.roleId ?? '';

        document.querySelectorAll('.permission-checkbox').forEach(checkbox => {
            checkbox.checked = currentUser.permissionIds.includes(checkbox.value);
        });

        document.getElementById('editModal').style.display = 'block';
    }
    function closeEditModal() {
        document.getElementById('editModal').style.display = 'none';
    }

    // ===== Modal: Eliminar =====
    function openDeleteModalFromPanel() {
        if (!currentUser) return;
        document.getElementById('deleteForm').action = '/admin/users/' + currentUser.id;
        document.getElementById('deleteUserName').textContent = currentUser.name;
        document.getElementById('deleteModal').style.display = 'block';
    }
    function closeDeleteModal() {
        document.getElementById('deleteModal').style.display = 'none';
    }

    // Cerrar modales con la tecla ESC
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            closeCreateModal();
            closeEditModal();
            closeDeleteModal();
        }
    });
</script>
@endpush