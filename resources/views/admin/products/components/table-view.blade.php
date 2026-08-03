<div id="view-table" class="hidden bg-white border border-slate-200/80 rounded-xl shadow-sm overflow-hidden">
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
                                <div class="w-10 h-10 rounded-lg bg-orange-50 border border-orange-100 flex items-center justify-center text-orange-600 shrink-0 font-bold uppercase">
                                    {{ substr($product->name, 0, 2) }}
                                </div>
                                <div>
                                    <p class="font-bold text-slate-900">{{ $product->name }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="py-4 px-4 font-semibold text-slate-600">{{ $product->category->name ?? 'Sin categoría' }}</td>
                        <td class="py-4 px-4 font-mono text-slate-500">{{ $product->code }}</td>
                        <td class="py-4 px-4"><span class="font-bold text-slate-900">{{ $product->stock }}</span> pcs</td>
                        <td class="py-4 px-4">
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
                            <button type="button"
                                data-id="{{ $product->id }}" data-name="{{ $product->name }}" data-code="{{ $product->code }}" data-category_id="{{ $product->category_id }}" data-stock="{{ $product->stock }}" data-unit_cost="{{ $product->unit_cost }}" data-description="{{ $product->description }}"
                                onclick="openEditModal(this)"
                                class="inline-flex items-center p-1.5 text-slate-400 hover:text-orange-600 hover:bg-orange-50 rounded-lg transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            </button>
                            <button type="button" onclick="openDeleteModal({{ $product->id }})" class="inline-flex items-center p-1.5 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </td>
                        @endcan
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="py-8 text-center text-slate-500 font-medium">No hay productos que coincidan con la búsqueda.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>