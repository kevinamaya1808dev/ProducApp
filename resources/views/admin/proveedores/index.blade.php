@extends('layouts.app')

@section('content')
<div class="py-12" x-data="{ 
    openCreateModal: false, 
    openEditModal: false, 
    openDeleteModal: false, 
    activeProveedor: {},
    proveedores: @js($proveedores),
    search: '',
    get filteredProveedores() {
        const term = this.search.trim().toLowerCase();
        if (term === '') return this.proveedores;
        return this.proveedores.filter(p => {
            const haystack = `${p.nombre} ${p.contacto_nombre ?? ''} ${p.telefono ?? ''} ${p.email ?? ''}`.toLowerCase();
            return haystack.includes(term);
        });
    }
}">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

        <!-- Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="font-bold text-2xl text-slate-800 dark:text-white leading-tight">
                    {{ __('Gestión de Proveedores') }}
                </h2>
                <p class="text-sm text-slate-500 dark:text-stone-400 mt-1">Directorio independiente de proveedores e insumos.</p>
            </div>

            @can('proveedores.create')
                <button @click="openCreateModal = true" class="px-4 py-2.5 bg-orange-600 hover:bg-orange-700 text-white font-bold rounded-xl text-sm transition-colors shadow-sm shadow-orange-950/20 flex items-center gap-2 cursor-pointer">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Registrar Proveedor
                </button>
            @endcan
        </div>

        <!-- Tarjeta de resumen -->
        <div class="bg-white dark:bg-stone-900 rounded-xl border border-slate-200 dark:border-stone-800 p-4 inline-flex items-center gap-3 w-fit">
            <div class="w-9 h-9 rounded-lg bg-orange-500/10 flex items-center justify-center">
                <svg class="w-5 h-5 text-orange-600 dark:text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2M5 21H3m16 0v-6a2 2 0 00-2-2H7a2 2 0 00-2 2v6"></path></svg>
            </div>
            <div>
                <p class="text-[11px] font-bold text-slate-400 dark:text-stone-500 uppercase tracking-wide">Proveedores registrados</p>
                <p class="text-xl font-bold text-slate-800 dark:text-white" x-text="proveedores.length"></p>
            </div>
        </div>

        <!-- Buscador -->
        <div class="relative max-w-md">
            <svg class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 dark:text-stone-500 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11a6 6 0 11-12 0 6 6 0 0112 0z"></path></svg>
            <input 
                type="text" 
                x-model.debounce.150ms="search" 
                placeholder="Buscar por empresa, contacto, teléfono o correo..." 
                class="w-full pl-9 pr-3 py-2.5 rounded-xl border border-slate-200 dark:border-stone-800 bg-white dark:bg-stone-900 text-sm text-slate-700 dark:text-white placeholder:text-slate-400 dark:placeholder:text-stone-500 focus:outline-none focus:ring-2 focus:ring-orange-500/50 focus:border-orange-500 transition-colors"
            >
        </div>

        <!-- Tabla -->
        @include('admin.proveedores.components.proveedores-table')

    </div>

    <!-- Modales -->
    @can('proveedores.create')
        @include('admin.proveedores.modals.create-proveedor-modal')
    @endcan
    @can('proveedores.edit')
        @include('admin.proveedores.modals.edit-proveedor-modal')
    @endcan
    @can('proveedores.delete')
        @include('admin.proveedores.modals.delete-proveedor-modal')
    @endcan
</div>
@endsection