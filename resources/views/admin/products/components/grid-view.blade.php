<div id="view-grid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
    @forelse ($products as $product)
        @include('admin.products.components.product-card', ['product' => $product])
    @empty
        <div class="col-span-full py-12 text-center bg-white border border-slate-200 border-dashed rounded-xl">
            <svg class="mx-auto h-12 w-12 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
            <h3 class="mt-2 text-sm font-medium text-slate-900">No hay productos</h3>
            <p class="mt-1 text-xs text-slate-500">No se encontraron productos que coincidan con los filtros.</p>
        </div>
    @endforelse
</div>