@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Encabezado del Módulo -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Catálogo de Productos</h1>
            <p class="text-xs text-slate-500 mt-1">
                Gestión de insumos, prendas e inventario disponible para producción
            </p>
        </div>

        <div class="flex items-center gap-3">
            @can('manage-products')
            <button type="button" onclick="openModal('modal-create')" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 border border-transparent rounded-lg text-xs font-semibold text-white hover:bg-blue-700 shadow-sm transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Nuevo Producto
            </button>
            @endcan
        </div>
    </div>

    <!-- Barra de Filtros y Búsqueda -->
    <div class="bg-white p-4 rounded-xl border border-slate-200/80 shadow-sm flex flex-col sm:flex-row items-center justify-between gap-4">
        <div class="relative w-full sm:w-80">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>
            <input type="text" placeholder="Buscar por SKU, nombre..." class="w-full pl-9 pr-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>

        <div class="flex items-center gap-3 w-full sm:w-auto justify-end">
            <select class="bg-slate-50 border border-slate-200 text-xs font-medium text-slate-700 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">Todas las Categorías</option>
                <option value="outerwear">Outerwear</option>
                <option value="bottoms">Bottoms</option>
                <option value="tops">Tops</option>
            </select>

            <select class="bg-slate-50 border border-slate-200 text-xs font-medium text-slate-700 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">Todos los Estados</option>
                <option value="disponible">Disponible</option>
                <option value="produccion">En Producción</option>
                <option value="agotado">Agotado</option>
            </select>
        </div>
    </div>

    <!-- Tabla Principal de Productos -->
    <div class="bg-white border border-slate-200/80 rounded-xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/70 border-b border-slate-100 text-[11px] font-bold text-slate-400 uppercase tracking-wider">
                        <th class="py-3.5 px-6">Producto</th>
                        <th class="py-3.5 px-4">Categoría</th>
                        <th class="py-3.5 px-4">SKU / Código</th>
                        <th class="py-3.5 px-4">Stock</th>
                        <th class="py-3.5 px-4">Estado</th>
                        @can('manage-products')
                        <th class="py-3.5 px-6 text-right">Acciones</th>
                        @endcan
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs text-slate-700">
    @forelse ($products as $product)
        <tr class="hover:bg-slate-50/50 transition-colors">
            <td class="py-4 px-6">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-blue-50 border border-blue-100 flex items-center justify-center text-blue-600 shrink-0 font-bold uppercase">
                        <!-- Toma las primeras 2 letras del nombre -->
                        {{ substr($product->name, 0, 2) }}
                    </div>
                    <div>
                        <!-- Es vital mantener el id dinámico para que el JS nativo lo lea -->
                        <p class="font-bold text-slate-900" id="name_{{ $product->id }}">{{ $product->name }}</p>
                    </div>
                </div>
            </td>
            
            <td class="py-4 px-4 font-semibold text-slate-600">
                <!-- Si tienes categorías dinámicas, cámbialo por $product->category->name -->
                General
            </td>
            
            <td class="py-4 px-4 font-mono text-slate-500" id="sku_{{ $product->id }}">
                {{ $product->sku }}
            </td>
            
            <td class="py-4 px-4">
                <span class="font-bold text-slate-900" id="stock_{{ $product->id }}">{{ $product->stock }}</span> pcs
            </td>
            
            <td class="py-4 px-4">
                <!-- Lógica simple para cambiar el estado visual según el stock -->
                @if($product->stock > 0)
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-100">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-600"></span> Disponible
                    </span>
                @else
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-semibold bg-red-50 text-red-700 border border-red-100">
                        <span class="w-1.5 h-1.5 rounded-full bg-red-600"></span> Agotado
                    </span>
                @endif
            </td>
            
            @can('manage-products')
            <td class="py-4 px-6 text-right space-x-2">
                <!-- Botón Editar: Pasa el ID dinámico a la función JS -->
               <button type="button" 
    data-id="{{ $product->id }}"
    data-name="{{ $product->name }}"
    data-code="{{ $product->code }}"
    data-category_id="{{ $product->category_id }}"
    data-stock="{{ $product->stock }}"
    data-unit_cost="{{ $product->unit_cost }}"
    data-description="{{ $product->description }}"
    onclick="openEditModal(this)" 
    class="inline-flex items-center p-1.5 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors">
    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
</button>
                <!-- Botón Eliminar: Pasa el ID dinámico a la función JS -->
                <button type="button" onclick="openDeleteModal({{ $product->id }})" class="inline-flex items-center p-1.5 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                </button>
            </td>
            @endcan
        </tr>
    @empty
        <!-- Mensaje si la base de datos no tiene productos -->
        <tr>
            <td colspan="6" class="py-8 text-center text-slate-500 font-medium">
                No hay productos registrados en el sistema.
            </td>
        </tr>
    @endforelse
</tbody>
            </table>
        </div>
    </div>
</div>

<!-- Inclusión de Modales (Solo se renderizan si el usuario puede gestionar) -->
@can('manage-products')
    @include('admin.products.modals.create')
    @include('admin.products.modals.edit')
    @include('admin.products.modals.delete')
@endcan

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        window.openModal = function(modalId) {
            const modal = document.getElementById(modalId);
            if(modal) {
                modal.classList.remove('hidden');
                modal.classList.add('flex');
                document.body.style.overflow = 'hidden';
            }
        };

        window.closeModal = function(modalId) {
            const modal = document.getElementById(modalId);
            if(modal) {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
                document.body.style.overflow = 'auto';
            }
        };

        // Nueva función actualizada que recibe el botón completo
        window.openEditModal = function(button) {
            const id = button.dataset.id;
            
            // Llenar los inputs del modal
            document.getElementById('edit_name').value = button.dataset.name;
            document.getElementById('edit_code').value = button.dataset.code;
            document.getElementById('edit_category_id').value = button.dataset.category_id;
            document.getElementById('edit_stock').value = button.dataset.stock;
            document.getElementById('edit_unit_cost').value = button.dataset.unit_cost;
            document.getElementById('edit_description').value = button.dataset.description;
            
            // Actualizar la ruta del formulario
            const form = document.getElementById('edit_product_form');
            form.action = `/admin/products/${id}`; // Asegúrate que esta ruta coincida con tu archivo web.php
            
            openModal('modal-edit');
        };

        window.openDeleteModal = function(productId) {
            const form = document.getElementById('delete_product_form');
            form.action = `/admin/products/${productId}`; // Asegúrate que esta ruta coincida con tu archivo web.php
            openModal('modal-delete');
        };
    });
</script>
@endpush