@extends('layouts.app')

@section('content')
<div class="py-12" x-data="{ 
    openMaterialModal: false, 
    openRecipeModal: false, 
    openEditModal: false, 
    openDeleteModal: false, 
    openAddStockModal: false,
    openHistoryModal: false,
    activeMaterial: {},
    materialHistory: []
}">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        
        <!-- Encabezado de la Sección y Botones de Acción -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <h2 class="font-semibold text-xl text-slate-800 dark:text-stone-200 leading-tight">
                {{ __('Control de Almacén e Insumos') }}
            </h2>
            <div class="flex items-center gap-3">
                {{-- Historial: de solo lectura, cualquiera con view-almacen puede verlo --}}
                <a href="{{ route('admin.almacen.historial') }}" class="px-4 py-2 bg-white dark:bg-stone-800 border border-slate-300 dark:border-stone-700 hover:bg-slate-50 dark:hover:bg-stone-700 text-slate-700 dark:text-stone-300 font-medium rounded-lg text-sm transition-colors shadow-sm flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Historial General
                </a>

                {{-- Registrar Material / Vincular Receta: acciones de escritura --}}
                @can('manage-almacen')
                    <button @click="openMaterialModal = true" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg text-sm transition-colors shadow-sm flex items-center gap-2 cursor-pointer">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        Registrar Material
                    </button>
                    <button @click="openRecipeModal = true" class="px-4 py-2 bg-slate-800 hover:bg-slate-900 dark:bg-stone-700 dark:hover:bg-stone-600 text-white font-medium rounded-lg text-sm transition-colors shadow-sm flex items-center gap-2 cursor-pointer">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        Vincular Receta
                    </button>
                @endcan
            </div>
        </div>

        {{-- Banner de alerta cuando hay materiales en stock bajo --}}
        @if($lowStockMaterials->isNotEmpty())
            <div class="flex items-start gap-3 p-4 rounded-xl border border-amber-200 dark:border-amber-900/50 bg-amber-50 dark:bg-amber-950/30">
                <svg class="w-5 h-5 text-amber-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0z" />
                    <circle cx="12" cy="16.5" r="0.75" fill="currentColor" stroke="none" />
                </svg>
                <div>
                    <p class="text-sm font-bold text-amber-800 dark:text-amber-300">
                        {{ $lowStockMaterials->count() }} {{ $lowStockMaterials->count() === 1 ? 'material está' : 'materiales están' }} en o por debajo del stock mínimo
                    </p>
                    <p class="text-xs text-amber-700 dark:text-amber-400 mt-0.5">
                        {{ $lowStockMaterials->pluck('name')->implode(', ') }}. Se generó una incidencia automática por cada uno para su seguimiento.
                    </p>
                </div>
            </div>
        @endif

        <!-- Tabla de Inventario de Materiales Actuales -->
        @include('admin.almacen.components.inventory-table')

    </div>

    <!-- Modales de escritura: solo se cargan si el usuario puede gestionar el almacén -->
    @can('manage-almacen')
        @include('admin.almacen.modals.material-modal')
        @include('admin.almacen.modals.recipe-modal')
        @include('admin.almacen.modals.edit-material-modal')
        @include('admin.almacen.modals.delete-material-modal')
        @include('admin.almacen.modals.add-stock-modal')
    @endcan

    {{-- Historial: de solo lectura, no requiere manage-almacen --}}
    @include('admin.almacen.modals.stock-history-modal')
</div>
@endsection