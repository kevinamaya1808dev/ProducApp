@extends('layouts.app')

@section('content')

<div x-data="operatorsManager(@json($users), @json($roles))" class="p-6 max-w-[1600px] mx-auto overflow-hidden">

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
            <p class="text-sm text-slate-500 mt-1">Personal de producción &middot; {{ $users->count() }} registros</p>
        </div>

        @can('manage-operators')
        <button @click="openCreateModal()" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-lg text-sm font-semibold transition-all shadow-sm shadow-blue-600/30 flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Agregar Operario
        </button>
        @endcan
    </div>

    <!-- KPI Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-white border border-slate-200 rounded-xl p-5 shadow-sm">
            <p class="text-xs font-bold text-slate-500 tracking-wider uppercase mb-1">Total Usuarios</p>
            <p class="text-3xl font-black text-slate-900">{{ $users->count() }}</p>
        </div>
        <div class="bg-white border border-slate-200 rounded-xl p-5 shadow-sm">
            <p class="text-xs font-bold text-slate-500 tracking-wider uppercase mb-1">Administradores</p>
            <p class="text-3xl font-black text-blue-600">{{ $users->filter(fn($u) => $u->roles->contains('name', 'admin'))->count() }}</p>
        </div>
        <div class="bg-white border border-slate-200 rounded-xl p-5 shadow-sm">
            <p class="text-xs font-bold text-slate-500 tracking-wider uppercase mb-1">Operarios</p>
            <p class="text-3xl font-black text-emerald-600">{{ $users->filter(fn($u) => $u->roles->contains('name', 'operario'))->count() }}</p>
        </div>
        <div class="bg-white border border-slate-200 rounded-xl p-5 shadow-sm">
            <p class="text-xs font-bold text-slate-500 tracking-wider uppercase mb-1">Becarios</p>
            <p class="text-3xl font-black text-amber-500">{{ $users->filter(fn($u) => $u->roles->contains('name', 'becario'))->count() }}</p>
        </div>
    </div>

    <!-- Barra de Búsqueda -->
    <div class="flex flex-col lg:flex-row justify-between gap-4 mb-6 items-start lg:items-center">
        <div class="bg-white p-2.5 rounded-xl border border-slate-200 shadow-sm relative w-full sm:w-80">
            <svg class="w-4 h-4 text-slate-400 absolute left-4 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            <input type="text" x-model="search" placeholder="Buscar por nombre o correo..." class="w-full pl-8 pr-2 text-sm bg-transparent border-none focus:ring-0 text-slate-700 placeholder-slate-400 outline-none">
        </div>
    </div>

    <!-- Contenedor Principal -->
    <div class="flex items-start gap-6 relative">

        <!-- Grid de Tarjetas -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 flex-1 transition-all duration-300">
            <template x-for="user in filteredUsers" :key="user.id">
                <div @click="selectUser(user)" class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm hover:border-blue-300 hover:shadow-md transition-all cursor-pointer relative overflow-hidden group">
                    <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-blue-500 to-indigo-500"></div>
                    <div class="flex justify-between items-start mb-4">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 rounded-full bg-blue-600 text-white font-bold flex items-center justify-center text-sm shadow-sm" x-text="initials(user.name)"></div>
                            <div>
                                <h3 class="font-bold text-slate-900 text-base group-hover:text-blue-600 transition-colors" x-text="user.name"></h3>
                                <span class="inline-block bg-blue-50 text-blue-700 border border-blue-100 text-[10px] font-bold px-2 py-0.5 rounded-md mt-0.5" x-text="roleName(user)"></span>
                            </div>
                        </div>
                    </div>
                    <div class="pt-3 border-t border-slate-100 text-xs">
                        <p class="text-slate-400 font-medium uppercase tracking-wider text-[10px]">Correo</p>
                        <p class="font-bold text-slate-800 mt-0.5 truncate" x-text="user.email"></p>
                    </div>
                </div>
            </template>

            <template x-if="filteredUsers.length === 0">
                <p class="text-sm text-slate-400 col-span-full text-center py-10">No se encontraron usuarios.</p>
            </template>
        </div>

        <!-- Panel Lateral -->
        <div x-show="isPanelOpen"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-x-10"
             x-transition:enter-end="opacity-100 translate-x-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-x-0"
             x-transition:leave-end="opacity-0 translate-x-10"
             class="w-full max-w-[360px] shrink-0 bg-white border border-slate-200 rounded-2xl shadow-sm hidden lg:flex flex-col relative"
             style="display: none;">

            <div class="p-5 border-b border-slate-100 flex justify-between items-center bg-slate-50/50 rounded-t-2xl">
                <h3 class="font-bold text-slate-900 text-sm">Perfil del Usuario</h3>
                <button @click="closePanel()" class="text-slate-400 hover:text-slate-600">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <div class="p-5 flex-1 overflow-y-auto" x-show="activeUser">
                <div class="text-center mb-6">
                    <div class="w-16 h-16 rounded-full bg-blue-600 text-white font-black text-xl flex items-center justify-center mx-auto mb-3 shadow-md" x-text="activeUser ? initials(activeUser.name) : ''"></div>
                    <h2 class="text-lg font-black text-slate-900" x-text="activeUser?.name"></h2>
                    <span class="inline-block bg-blue-50 text-blue-700 border border-blue-100 text-[11px] font-bold px-2.5 py-0.5 rounded-md mt-1" x-text="activeUser ? roleName(activeUser) : ''"></span>
                </div>

                <ul class="space-y-3.5 text-sm mb-6">
                    <li class="flex justify-between items-center border-b border-slate-50 pb-2.5">
                        <span class="text-slate-400 font-semibold text-xs uppercase tracking-wider">Correo</span>
                        <span class="text-slate-800 font-bold truncate max-w-[180px]" x-text="activeUser?.email"></span>
                    </li>
                </ul>
            </div>

            <div class="p-4 border-t border-slate-100 bg-slate-50/50 rounded-b-2xl flex gap-2">
                @can('manage-operators')
                <button @click="openEditModal(activeUser)" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-semibold text-sm py-2.5 rounded-lg transition-colors shadow-sm flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                    Editar
                </button>
                <button @click="openDeleteModal(activeUser)" class="bg-red-50 hover:bg-red-100 text-red-600 font-semibold text-sm py-2.5 px-4 rounded-lg transition-colors shadow-sm">
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
    document.addEventListener('alpine:init', () => {
        Alpine.data('operatorsManager', (initialUsers, roles) => ({
            users: initialUsers,
            roles: roles,
            search: '',
            isPanelOpen: false,
            isCreateModalOpen: false,
            isEditModalOpen: false,
            isDeleteModalOpen: false,
            activeUser: null,
            deleteTarget: null,
            formData: { id: null, name: '', email: '', password: '', role_id: '' },

            get filteredUsers() {
                if (!this.search) return this.users;
                const q = this.search.toLowerCase();
                return this.users.filter(u =>
                    u.name.toLowerCase().includes(q) || u.email.toLowerCase().includes(q)
                );
            },

            initials(name) {
                if (!name) return '';
                return name.split(' ').slice(0, 2).map(n => n[0]).join('').toUpperCase();
            },

            roleName(user) {
                return user.roles && user.roles.length ? user.roles[0].name : 'Sin rol';
            },

            selectUser(user) {
                this.activeUser = user;
                this.isPanelOpen = true;
            },
            closePanel() {
                this.isPanelOpen = false;
            },

            openCreateModal() {
                this.formData = { id: null, name: '', email: '', password: '', role_id: '' };
                this.isCreateModalOpen = true;
            },
            openEditModal(user) {
                if (!user) return;
                this.formData = {
                    id: user.id,
                    name: user.name,
                    email: user.email,
                    password: '',
                    role_id: user.roles && user.roles.length ? user.roles[0].id : ''
                };
                this.isEditModalOpen = true;
            },
            openDeleteModal(user) {
                if (!user) return;
                this.deleteTarget = user;
                this.isDeleteModalOpen = true;
            },
            closeCreateModal() { this.isCreateModalOpen = false; },
            closeEditModal() { this.isEditModalOpen = false; },
            closeDeleteModal() { this.isDeleteModalOpen = false; },
        }))
    })
</script>
@endpush