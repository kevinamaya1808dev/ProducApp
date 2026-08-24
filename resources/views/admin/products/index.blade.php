@extends('layouts.app')

@section('content')
<div class="space-y-6">

    @include('admin.products.components.header')

    @include('admin.products.components.filters')

    @include('admin.products.components.grid-view', ['products' => $products])

    @include('admin.products.components.table-view', ['products' => $products])

    @if($products->hasPages())
    <div class="pt-2">
        {{ $products->links() }}
    </div>
    @endif
</div>

@can('manage-products')
    @include('admin.products.modals.create')
    @include('admin.products.modals.edit')
    @include('admin.products.modals.delete')
@endcan

@endsection

@push('scripts')
<script>
    // 1. Lógica para alternar las Vistas (Grid vs Tabla) y guardarlo en LocalStorage
    document.addEventListener('DOMContentLoaded', function() {
        const savedView = localStorage.getItem('producapp_product_view') || 'grid';
        toggleView(savedView);
    });

    window.toggleView = function(viewType) {
        const viewGrid = document.getElementById('view-grid');
        const viewTable = document.getElementById('view-table');
        const btnGrid = document.getElementById('btn-grid');
        const btnTable = document.getElementById('btn-table');

        localStorage.setItem('producapp_product_view', viewType);

        if(viewType === 'grid') {
            viewGrid.classList.remove('hidden');
            viewGrid.classList.add('grid');
            viewTable.classList.add('hidden');

            btnGrid.classList.add('bg-slate-900', 'dark:bg-stone-100', 'text-white', 'dark:text-stone-900');
            btnGrid.classList.remove('text-slate-500', 'dark:text-stone-400', 'hover:text-slate-900', 'dark:hover:text-stone-200');
            btnTable.classList.remove('bg-slate-900', 'dark:bg-stone-100', 'text-white', 'dark:text-stone-900');
            btnTable.classList.add('text-slate-500', 'dark:text-stone-400', 'hover:text-slate-900', 'dark:hover:text-stone-200');
        } else {
            viewTable.classList.remove('hidden');
            viewGrid.classList.add('hidden');
            viewGrid.classList.remove('grid');

            btnTable.classList.add('bg-slate-900', 'dark:bg-stone-100', 'text-white', 'dark:text-stone-900');
            btnTable.classList.remove('text-slate-500', 'dark:text-stone-400', 'hover:text-slate-900', 'dark:hover:text-stone-200');
            btnGrid.classList.remove('bg-slate-900', 'dark:bg-stone-100', 'text-white', 'dark:text-stone-900');
            btnGrid.classList.add('text-slate-500', 'dark:text-stone-400', 'hover:text-slate-900', 'dark:hover:text-stone-200');
        }
    };

    // 2. Lógica de los Modales y Control de Imágenes Integrado
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

        window.openEditModal = function(button) {
            const id = button.dataset.id;

            document.getElementById('edit_name').value = button.dataset.name;
            document.getElementById('edit_code').value = button.dataset.code;
            document.getElementById('edit_category_id').value = button.dataset.category_id;
            document.getElementById('edit_stock').value = button.dataset.stock;
            document.getElementById('edit_unit_cost').value = button.dataset.unit_cost;
            document.getElementById('edit_description').value = button.dataset.description;

            // Carga y previsualización de la imagen actual del producto en el modal
            const imageUrl = button.dataset.image;
            const previewImage = document.getElementById('edit_image_preview');
            const placeholder = document.getElementById('image_placeholder');
            const removeBtn = document.getElementById('remove_image_btn');
            const fileInput = document.getElementById('edit_image_input');

            if (fileInput) fileInput.value = ''; // Limpiar input file

            if (imageUrl && imageUrl.trim() !== '') {
                previewImage.src = imageUrl;
                previewImage.classList.remove('hidden');
                placeholder.classList.add('hidden');
                removeBtn.classList.remove('hidden');
            } else {
                window.clearEditImage();
            }

            const form = document.getElementById('edit_product_form');
            form.action = `/admin/products/${id}`;

            openModal('modal-edit');
        };

        window.openDeleteModal = function(productId) {
            const form = document.getElementById('delete_product_form');
            form.action = `/admin/products/${productId}`;
            openModal('modal-delete');
        };
    });

    // 3. Funciones globales para previsualizar y limpiar la imagen seleccionada
    window.previewEditImage = function(event) {
        const reader = new FileReader();
        const file = event.target.files[0];
        
        const previewImage = document.getElementById('edit_image_preview');
        const placeholder = document.getElementById('image_placeholder');
        const removeBtn = document.getElementById('remove_image_btn');

        if (file) {
            reader.onload = function(e) {
                previewImage.src = e.target.result;
                previewImage.classList.remove('hidden');
                placeholder.classList.add('hidden');
                removeBtn.classList.remove('hidden');
            }
            reader.readAsDataURL(file);
        }
    };

    window.clearEditImage = function(event) {
        if (event) event.stopPropagation(); // Evita que se abra el explorador de archivos al dar click en la X

        const input = document.getElementById('edit_image_input');
        const previewImage = document.getElementById('edit_image_preview');
        const placeholder = document.getElementById('image_placeholder');
        const removeBtn = document.getElementById('remove_image_btn');

        if (input) input.value = '';
        if (previewImage) previewImage.src = '';
        
        if (previewImage) previewImage.classList.add('hidden');
        if (placeholder) placeholder.classList.remove('hidden');
        if (removeBtn) removeBtn.classList.add('hidden');
    };
</script>
@endpush