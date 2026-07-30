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
            Nuevo Operario
        </button>
        @endcan
    </div>

    <!-- KPI Cards (Sin Cambios) -->
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
                    data-turno="{{ $user->turno ?? 'Sin asignar' }}"
                    data-estacion="{{ $user->planta ?? 'N/A' }}"
                    data-active="{{ $user->active }}"
                    data-notas="{{ $user->notas ?? '' }}"
                    data-created="{{ $user->created_at->translatedFormat('M Y') }}"
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

        <!-- Panel Lateral (Actualizado según Maqueta) -->
        <div id="userPanel" class="w-full max-w-[380px] shrink-0 bg-white border border-slate-200 rounded-2xl shadow-sm hidden lg:flex flex-col relative" style="display:none;">

            <div class="p-4 flex justify-between items-center bg-white rounded-t-2xl">
                <h3 class="font-bold text-slate-900 text-lg">Perfil del Operario</h3>
                <button type="button" onclick="closePanel()" class="text-slate-400 hover:text-slate-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <div class="p-5 flex-1 overflow-y-auto">
                <!-- Avatar y Cabecera -->
                <div class="text-center mb-6">
                    <div id="panelInitials" class="w-20 h-20 rounded-full bg-blue-500 text-white font-bold text-2xl flex items-center justify-center mx-auto mb-3"></div>
                    <h2 id="panelName" class="text-xl font-bold text-slate-900"></h2>
                    <span id="panelRole" class="block text-blue-600 bg-blue-50 px-3 py-1 rounded-full text-xs font-semibold w-max mx-auto mt-2"></span>
                    
                    <div class="flex items-center justify-center gap-2 mt-3">
                        <span id="panelStatus" class="px-3 py-1 text-xs font-semibold rounded-full border"></span>
                        <span id="panelTurno" class="px-3 py-1 text-blue-600 bg-blue-50 border border-blue-100 text-xs font-semibold rounded-full"></span>
                    </div>
                </div>

                <!-- Eficiencia -->
                <div class="mb-6">
                    <div class="flex justify-between items-end mb-2">
                        <span class="text-xs font-bold text-slate-400 tracking-wider uppercase">Eficiencia</span>
                        <span class="text-base font-bold text-slate-800">94%</span>
                    </div>
                    <div class="w-full bg-slate-100 rounded-full h-2.5">
                        <div class="bg-blue-600 h-2.5 rounded-full" style="width: 94%"></div>
                    </div>
                </div>

                <!-- Detalles de Lista -->
                <ul class="space-y-4 text-sm mb-6">
                    <li class="flex justify-between items-center">
                        <span class="text-slate-400 font-semibold text-xs uppercase tracking-wider">Estación</span>
                        <span id="panelEstacion" class="text-slate-800 font-bold"></span>
                    </li>
                    <li class="flex justify-between items-center">
                        <span class="text-slate-400 font-semibold text-xs uppercase tracking-wider">Órdenes</span>
                        <span class="text-slate-800 font-bold">312</span>
                    </li>
                    <li class="flex justify-between items-center">
                        <span class="text-slate-400 font-semibold text-xs uppercase tracking-wider">Orden Actual</span>
                        <span class="text-slate-800 font-bold">ORD-2024-0091</span>
                    </li>
                    <li class="flex justify-between items-center">
                        <span class="text-slate-400 font-semibold text-xs uppercase tracking-wider">Correo</span>
                        <span id="panelEmail" class="text-slate-800 font-bold truncate max-w-[180px]"></span>
                    </li>
                    <li class="flex justify-between items-center">
                        <span class="text-slate-400 font-semibold text-xs uppercase tracking-wider">Alta</span>
                        <span id="panelAlta" class="text-slate-800 font-bold"></span>
                    </li>
                </ul>

                <!-- Habilidades (Visuales Estáticas según maqueta) -->
                <div class="mb-6">
                    <span class="text-slate-400 font-semibold text-xs uppercase tracking-wider block mb-2">Habilidades</span>
                    <div class="flex flex-wrap gap-2">
                        <span class="px-3 py-1 bg-white border border-blue-200 text-blue-600 rounded-full text-xs font-semibold">Costura</span>
                        <span class="px-3 py-1 bg-white border border-blue-200 text-blue-600 rounded-full text-xs font-semibold">Acabados</span>
                        <span class="px-3 py-1 bg-white border border-blue-200 text-blue-600 rounded-full text-xs font-semibold">Control Calidad</span>
                    </div>
                </div>
            </div>

            <!-- Botones de Acción Panel -->
            <div class="p-5 border-t border-slate-100 flex flex-col gap-3">
                @can('manage-operators')
                <button type="button" onclick="openEditModalFromPanel()" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold text-sm py-2.5 rounded-xl transition-colors shadow-sm">
                    Editar Perfil
                </button>
                <form id="statusFormPanel" method="POST" class="w-full">
                    @csrf
                    @method('PUT')
                    <!-- Campos ocultos para mantener los datos al cambiar estado -->
                    <input type="hidden" name="name" id="statusFormName">
                    <input type="hidden" name="email" id="statusFormEmail">
                    <input type="hidden" name="role_id" id="statusFormRole">
                    <input type="hidden" name="active" id="statusFormActive">
                    <button type="button" onclick="toggleStatusFromPanel()" class="w-full bg-orange-50 hover:bg-orange-100 text-orange-600 border border-orange-200 font-semibold text-sm py-2.5 rounded-xl transition-colors shadow-sm">
                        Dar de baja / Alta
                    </button>
                </form>
                <button type="button" onclick="openDeleteModalFromPanel()" class="w-full bg-red-50 hover:bg-red-100 text-red-600 border border-red-200 font-semibold text-sm py-2.5 rounded-xl transition-colors shadow-sm">
                    Eliminar Registro
                </button>
                @else
                <div class="text-center text-xs text-slate-400 py-1 w-full">
                    Modo visualización (Sin privilegios)
                </div>
                @endcan
            </div>
        </div>
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

        document.getElementById('noResults').style.display = visibleCount === 0 ? '' : 'none';
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
            created: card.dataset.created
        };

        // Llenar panel lateral
        document.getElementById('panelInitials').textContent = currentUser.initials;
        document.getElementById('panelName').textContent = currentUser.name;
        document.getElementById('panelRole').textContent = currentUser.roleName;
        document.getElementById('panelEmail').textContent = currentUser.email;
        document.getElementById('panelTurno').textContent = currentUser.turno;
        document.getElementById('panelEstacion').textContent = currentUser.estacion;
        document.getElementById('panelAlta').textContent = currentUser.created;

        const statusBadge = document.getElementById('panelStatus');
        if(currentUser.active) {
            statusBadge.textContent = 'Activo';
            statusBadge.className = 'px-3 py-1 text-xs font-semibold rounded-full border border-emerald-200 text-emerald-600 bg-emerald-50';
        } else {
            statusBadge.textContent = 'Inactivo';
            statusBadge.className = 'px-3 py-1 text-xs font-semibold rounded-full border border-red-200 text-red-600 bg-red-50';
        }

        document.getElementById('userPanel').style.display = 'flex';
    }

    function closePanel() {
        document.getElementById('userPanel').style.display = 'none';
        currentUser = null;
    }

    // Modal Crear
    function openCreateModal() {
        document.getElementById('createModal').style.display = 'block';
    }
    function closeCreateModal() {
        document.getElementById('createModal').style.display = 'none';
    }

    // Modal Editar
    function openEditModalFromPanel() {
        if (!currentUser) return;
        document.getElementById('editForm').action = '/admin/users/' + currentUser.id;
        document.getElementById('editName').value = currentUser.name;
        document.getElementById('editEmail').value = currentUser.email;
        document.getElementById('editPassword').value = '';
        document.getElementById('editRoleId').value = currentUser.roleId ?? '';
        document.getElementById('editTurno').value = currentUser.turno !== 'Sin asignar' ? currentUser.turno : '';
        document.getElementById('editEstacion').value = currentUser.estacion !== 'N/A' ? currentUser.estacion : '';
        document.getElementById('editActive').value = currentUser.active ? '1' : '0';
        document.getElementById('editNotas').value = currentUser.notas ?? '';

        document.getElementById('editModal').style.display = 'block';
    }
    function closeEditModal() {
        document.getElementById('editModal').style.display = 'none';
    }

    // Funcionalidad Dar de Baja rápida
    // Abrir el modal en lugar de usar confirm()
    function toggleStatusFromPanel() {
        if(!currentUser) return;
        
        // Colocar el nombre del operario en el texto del modal
        document.getElementById('deactivateUserName').textContent = currentUser.name;
        
        // Mostrar el modal
        document.getElementById('deactivateModal').style.display = 'block';
    }

    // Cerrar el modal
    function closeDeactivateModal() {
        document.getElementById('deactivateModal').style.display = 'none';
    }

    // Ejecutar el submit del formulario oculto cuando se confirme
    function confirmDeactivate() {
        if(!currentUser) return;
        
        document.getElementById('statusFormPanel').action = '/admin/users/' + currentUser.id;
        document.getElementById('statusFormName').value = currentUser.name;
        document.getElementById('statusFormEmail').value = currentUser.email;
        document.getElementById('statusFormRole').value = currentUser.roleId;
        
        // Si el modal es específicamente para "Dar de baja", forzamos el valor a 0 (Inactivo)
        document.getElementById('statusFormActive').value = '0'; 
        
        document.getElementById('statusFormPanel').submit();
    }

    // Modal Eliminar
    function openDeleteModalFromPanel() {
        if (!currentUser) return;
        document.getElementById('deleteForm').action = '/admin/users/' + currentUser.id;
        document.getElementById('deleteUserName').textContent = currentUser.name;
        document.getElementById('deleteModal').style.display = 'block';
    }
    function closeDeleteModal() {
        document.getElementById('deleteModal').style.display = 'none';
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