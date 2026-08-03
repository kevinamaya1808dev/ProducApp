<div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
    <div>
        <h1 class="text-2xl font-bold text-slate-900">Productos</h1>
        <p class="text-sm text-slate-500 mt-1">
            Catálogo de productos manufacturados • {{ $products->total() ?? 0 }} registros
        </p>
    </div>

    @can('manage-products')
    <button type="button" onclick="openModal('modal-create')" class="px-5 py-2.5 bg-orange-600 text-white rounded-lg text-sm font-semibold hover:bg-orange-700 transition-colors shadow-sm shadow-orange-600/20">
        + Nuevo Producto
    </button>
    @endcan
</div>