@extends('layouts.app')

@section('content')
<div class="space-y-6">

    @include('admin.products.components.header')
    @include('admin.products.components.filters')
    @include('admin.products.components.grid-view', ['products' => $products])
    @include('admin.products.components.table-view', ['products' => $products])

    @if($products->hasPages())
    <div class="pt-2">{{ $products->links() }}</div>
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
// ── Vista Grid / Tabla ────────────────────────────────────────────────────────
const ACTIVE   = ['bg-stone-900','dark:bg-stone-100','text-white','dark:text-stone-900'];
const INACTIVE = ['text-stone-500','dark:text-stone-400','hover:text-stone-900','dark:hover:text-stone-200'];

function toggleView(type) {
    localStorage.setItem('producapp_product_view', type);
    const isGrid = type === 'grid';
    document.getElementById('view-grid').classList.toggle('hidden', !isGrid);
    document.getElementById('view-grid').classList.toggle('grid',   isGrid);
    document.getElementById('view-table').classList.toggle('hidden', isGrid);
    const btnGrid  = document.getElementById('btn-grid');
    const btnTable = document.getElementById('btn-table');
    btnGrid.classList.toggle(...ACTIVE,   isGrid);  btnGrid.classList.toggle(...INACTIVE, !isGrid);
    btnTable.classList.toggle(...ACTIVE, !isGrid);  btnTable.classList.toggle(...INACTIVE, isGrid);
}

document.addEventListener('DOMContentLoaded', () => {
    toggleView(localStorage.getItem('producapp_product_view') || 'grid');
});

// ── Helpers de modal ──────────────────────────────────────────────────────────
window.openModal = id => {
    const m = document.getElementById(id);
    if (m) { m.classList.remove('hidden'); m.classList.add('flex'); document.body.style.overflow = 'hidden'; }
};
window.closeModal = id => {
    const m = document.getElementById(id);
    if (m) { m.classList.add('hidden'); m.classList.remove('flex'); document.body.style.overflow = 'auto'; }
};

// ── Referencias DOM de imagen (reutilizadas en 3 funciones) ──────────────────
const imgEls = () => ({
    preview:     document.getElementById('edit_image_preview'),
    placeholder: document.getElementById('image_placeholder'),
    removeBtn:   document.getElementById('remove_image_btn'),
    input:       document.getElementById('edit_image_input'),
});

// ── Preview de imagen seleccionada ────────────────────────────────────────────
window.previewEditImage = e => {
    const file = e.target.files[0];
    if (!file) return;
    const { preview, placeholder, removeBtn } = imgEls();
    const reader = new FileReader();
    reader.onload = ev => {
        if (preview) { preview.src = ev.target.result; preview.classList.remove('hidden'); }
        placeholder?.classList.add('hidden');
        removeBtn?.classList.remove('hidden');
    };
    reader.readAsDataURL(file);
};

// ── Limpiar imagen ────────────────────────────────────────────────────────────
window.clearEditImage = ev => {
    ev?.stopPropagation();
    const { preview, placeholder, removeBtn, input } = imgEls();
    if (input)   input.value = '';
    if (preview) { preview.src = ''; preview.classList.add('hidden'); }
    placeholder?.classList.remove('hidden');
    removeBtn?.classList.add('hidden');
};

// ── Modal editar producto ─────────────────────────────────────────────────────
window.openEditModal = btn => {
    const d = btn.dataset;
    ['name','code','stock','unit_cost','description'].forEach(k => {
        const el = document.getElementById('edit_' + k); if (el) el.value = d[k] ?? '';
    });

    const catInput = document.getElementById('edit_category_id');
    if (catInput) { catInput.value = d.category_id || ''; catInput.dispatchEvent(new Event('change')); }
    window.dispatchEvent(new CustomEvent('set-edit-category', { detail: d.category_id }));

    const { preview, placeholder, removeBtn, input } = imgEls();
    if (input) input.value = '';
    if (d.image?.trim()) {
        if (preview) { preview.src = d.image; preview.classList.remove('hidden'); }
        placeholder?.classList.add('hidden');
        removeBtn?.classList.remove('hidden');
    } else {
        window.clearEditImage();
    }

    const form = document.getElementById('edit_product_form');
    if (form) form.action = `/admin/products/${d.id}`;
    openModal('modal-edit');
};

// ── Modal eliminar producto ───────────────────────────────────────────────────
window.openDeleteModal = id => {
    const form = document.getElementById('delete_product_form');
    if (form) form.action = `/admin/products/${id}`;
    openModal('modal-delete');
};
</script>
@endpush 