<div class="bg-white dark:bg-stone-900 p-6 rounded-xl shadow-sm border border-slate-200 dark:border-stone-800 overflow-hidden">
    <h3 class="text-md font-bold text-slate-800 dark:text-white mb-4">Stock Actual en Almacén</h3>
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm text-slate-600 dark:text-stone-300">
            <thead class="bg-slate-50 dark:bg-stone-950/60 text-xs uppercase font-bold text-slate-500 dark:text-stone-500">
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
            <tbody class="divide-y divide-slate-200 dark:divide-stone-800">
                @forelse($materials as $mat)
                    <tr class="{{ $mat->stock_actual <= $mat->stock_minimo ? 'bg-amber-50/50 dark:bg-amber-500/5' : '' }}">
                        <td class="px-4 py-3 font-mono text-xs text-slate-500 dark:text-stone-400">{{ $mat->sku }}</td>
                        <td class="px-4 py-3 font-medium text-slate-800 dark:text-white">{{ $mat->name }}</td>
                        <td class="px-4 py-3 font-bold {{ $mat->stock_actual <= $mat->stock_minimo ? 'text-amber-600 dark:text-amber-400' : 'text-emerald-600 dark:text-emerald-400' }}">
                            {{ $mat->stock_actual }}
                        </td>
                        <td class="px-4 py-3 text-slate-500 dark:text-stone-400">{{ $mat->stock_minimo }}</td>
                        <td class="px-4 py-3 text-slate-500 dark:text-stone-400">{{ $mat->proveedor ?? '—' }}</td>
                        <td class="px-4 py-3 text-slate-500 dark:text-stone-400">{{ $mat->unit }}</td>
                        <td class="px-4 py-3 text-right">
                            <div class="flex items-center justify-end gap-2">
                                @can('manage-almacen')
                                    <button @click="activeMaterial = {{ json_encode($mat) }}; openAddStockModal = true" title="Agregar Stock" class="w-8 h-8 flex items-center justify-center rounded-lg bg-slate-100 hover:bg-emerald-100 text-slate-600 hover:text-emerald-600 dark:bg-stone-800 dark:hover:bg-emerald-500/10 dark:border dark:border-stone-700 dark:hover:border-emerald-500/30 dark:text-stone-300 dark:hover:text-emerald-400 transition-colors cursor-pointer">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                    </button>
                                @endcan

                                <button @click="activeMaterial = {{ json_encode($mat) }}; materialHistory = {{ json_encode($mat->stockLogs) }}; openHistoryModal = true" title="Ver Historial" class="w-8 h-8 flex items-center justify-center rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-600 dark:bg-stone-800 dark:hover:bg-stone-700 dark:border dark:border-stone-700 dark:text-stone-300 dark:hover:text-white transition-colors cursor-pointer">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                </button>

                                @can('manage-almacen')
                                    <button @click="activeMaterial = {{ json_encode($mat) }}; openEditModal = true" title="Editar" class="w-8 h-8 flex items-center justify-center rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-600 dark:bg-stone-800 dark:hover:bg-stone-700 dark:border dark:border-stone-700 dark:text-stone-300 dark:hover:text-white transition-colors cursor-pointer">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    </button>
                                    <button @click="activeMaterial = {{ json_encode($mat) }}; openDeleteModal = true" title="Eliminar" class="w-8 h-8 flex items-center justify-center rounded-lg bg-slate-100 hover:bg-rose-100 text-slate-600 hover:text-rose-600 dark:bg-stone-800 dark:hover:bg-rose-500/10 dark:border dark:border-stone-700 dark:hover:border-rose-500/30 dark:text-stone-300 dark:hover:text-rose-400 transition-colors cursor-pointer">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                @endcan
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-4 py-4 text-center text-slate-400 dark:text-stone-500">No hay materiales registrados en almacén.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>