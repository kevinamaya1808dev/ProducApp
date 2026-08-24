@extends('layouts.app')

@section('content')
<div class="py-12" x-data="{ 
    openMaterialModal: false, 
    openRecipeModal: false, 
    openEditModal: false, 
    openDeleteModal: false, 
    activeMaterial: {} 
}">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        
        <!-- Encabezado de la Sección y Botones de Acción -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <h2 class="font-semibold text-xl text-slate-800 dark:text-stone-200 leading-tight">
                {{ __('Control de Almacén e Insumos') }}
            </h2>
            <div class="flex items-center gap-3">
                <button @click="openMaterialModal = true" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg text-sm transition-colors shadow-sm flex items-center gap-2 cursor-pointer">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Registrar Material
                </button>
                <button @click="openRecipeModal = true" class="px-4 py-2 bg-slate-800 hover:bg-slate-900 dark:bg-stone-700 dark:hover:bg-stone-600 text-white font-medium rounded-lg text-sm transition-colors shadow-sm flex items-center gap-2 cursor-pointer">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Vincular Receta
                </button>
            </div>
        </div>

        <!-- Tabla de Inventario de Materiales Actuales -->
        @include('admin.almacen.partials.inventory-table')

    </div>

    <!-- Modales de Formularios -->
    @include('admin.almacen.modals.material-modal')
    @include('admin.almacen.modals.recipe-modal')
    @include('admin.almacen.modals.edit-material-modal')
    @include('admin.almacen.modals.delete-material-modal')
</div>
@endsection