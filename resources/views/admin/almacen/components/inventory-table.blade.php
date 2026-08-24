<div class="bg-white dark:bg-stone-800 p-6 rounded-xl shadow-sm border border-slate-200 dark:border-stone-700 overflow-hidden">
    <h3 class="text-md font-bold text-slate-800 dark:text-stone-200 mb-4">Stock Actual en Almacén</h3>
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm text-slate-600 dark:text-stone-300">
            <thead class="bg-slate-50 dark:bg-stone-900 text-xs uppercase text-slate-500">
                <tr>
                    <th class="px-4 py-3">SKU</th>
                    <th class="px-4 py-3">Material</th>
                    <th class="px-4 py-3">Stock Actual</th>
                    <th class="px-4 py-3">Mínimo</th>
                    <th class="px-4 py-3">Proveedor</th>
                    <th class="px-4 py-3">Unidad</th>
                    <th class="px-4 py-3 text-right">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200 dark:divide-stone-700">
                @forelse($materials as $mat)
                    <tr class="{{ $mat->stock_actual <= $mat->stock_minimo ? 'bg-amber-50/50 dark:bg-amber-950/20' : '' }}">
                        <td class="px-4 py-3 font-mono text-xs">{{ $mat->sku }}</td>
                        <td class="px-4 py-3 font-medium text-slate-800 dark:text-stone-200">{{ $mat->name }}</td>
                        <td class="px-4 py-3 font-bold {{ $mat->stock_actual <= $mat->stock_minimo ? 'text-amber-600 dark:text-amber-400' : 'text-emerald-600 dark:text-emerald-400' }}">
                            {{ $mat->stock_actual }}
                        </td>
                        <td class="px-4 py-3">{{ $mat->stock_minimo }}</td>
                        <td class="px-4 py-3">{{ $mat->proveedor ?? '—' }}</td>
                        <td class="px-4 py-3">{{ $mat->unit }}</td>
                        <td class="px-4 py-3 text-right space-x-2">
                            <!-- Botón Editar -->
                            <button @click="activeMaterial = {{ json_encode($mat) }}; openEditModal = true" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 font-medium text-xs cursor-pointer inline-flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                Editar
                            </button>
                            <!-- Botón Eliminar -->
                            <button @click="activeMaterial = {{ json_encode($mat) }}; openDeleteModal = true" class="text-rose-600 hover:text-rose-800 dark:text-rose-400 dark:hover:text-rose-300 font-medium text-xs cursor-pointer inline-flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                Eliminar
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-4 py-4 text-center text-slate-400">No hay materiales registrados en almacén.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>