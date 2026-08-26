<div class="bg-white dark:bg-stone-800 p-6 rounded-xl shadow-sm border border-slate-200 dark:border-stone-700">
    <h3 class="text-md font-bold text-slate-800 dark:text-stone-200 mb-4">Stock Actual en Almacén</h3>
    <div class="w-full">
        <table class="w-full text-left text-sm text-slate-600 dark:text-stone-300 align-middle">
            <thead class="bg-slate-50 dark:bg-stone-900/80 text-xs uppercase text-slate-500 dark:text-stone-400 border-b border-slate-200 dark:border-stone-700">
                <tr>
                    <th class="px-2.5 py-3">SKU</th>
                    <th class="px-2.5 py-3">Material</th>
                    <th class="px-2.5 py-3">Stock</th>
                    <th class="px-2.5 py-3">Mín.</th>
                    <th class="px-2.5 py-3">Proveedor</th>
                    <th class="px-2.5 py-3">Unidad</th>
                    <th class="px-2.5 py-3 text-right">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200 dark:divide-stone-700/60">
                @forelse($materials as $mat)
                    <tr class="hover:bg-slate-50/50 dark:hover:bg-stone-700/30 transition-colors {{ $mat->stock_actual <= $mat->stock_minimo ? 'bg-amber-50/50 dark:bg-amber-950/20' : '' }}">
                        <td class="px-2.5 py-3 font-mono text-xs font-semibold text-slate-700 dark:text-stone-300 whitespace-nowrap">{{ $mat->sku }}</td>
                        <td class="px-2.5 py-3 font-medium text-slate-800 dark:text-stone-200 leading-snug break-words max-w-[180px]">{{ $mat->name }}</td>
                        <td class="px-2.5 py-3 font-bold whitespace-nowrap {{ $mat->stock_actual <= $mat->stock_minimo ? 'text-amber-600 dark:text-amber-400' : 'text-emerald-600 dark:text-emerald-400' }}">
                            {{ $mat->stock_actual }}
                        </td>
                        <td class="px-2.5 py-3 whitespace-nowrap">{{ $mat->stock_minimo }}</td>
                        <td class="px-2.5 py-3 text-xs text-slate-600 dark:text-stone-300 truncate max-w-[120px]" title="{{ $mat->proveedor }}">{{ $mat->proveedor ?? '—' }}</td>
                        <td class="px-2.5 py-3 whitespace-nowrap text-xs">{{ $mat->unit }}</td>
                        <td class="px-2.5 py-3 text-right">
                            {{-- Contenedor Flex con Wrap para que los botones se adapten sin crear scroll --}}
                            <div class="flex flex-wrap items-center justify-end gap-1.5">
                                @can('manage-almacen')
                                    {{-- Agregar Stock --}}
                                    <button @click="activeMaterial = {{ json_encode($mat) }}; openAddStockModal = true" title="Agregar Stock" class="px-2.5 py-1 rounded-lg bg-emerald-500/10 hover:bg-emerald-500/20 text-emerald-600 dark:text-emerald-400 font-semibold text-xs cursor-pointer inline-flex items-center gap-1 transition-colors border border-emerald-500/20">
                                        <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                        <span>Stock</span>
                                    </button>
                                @endcan

                                {{-- Ver historial --}}
                                <button @click="activeMaterial = {{ json_encode($mat) }}; materialHistory = {{ json_encode($mat->stockLogs) }}; openHistoryModal = true" title="Ver Historial" class="px-2.5 py-1 rounded-lg bg-slate-100 dark:bg-stone-700/60 hover:bg-slate-200 dark:hover:bg-stone-700 text-slate-600 dark:text-stone-300 font-medium text-xs cursor-pointer inline-flex items-center gap-1 transition-colors border border-slate-200 dark:border-stone-600">
                                    <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    <span>Historial</span>
                                </button>

                                @can('manage-almacen')
                                    <!-- Botón Editar -->
                                    <button @click="activeMaterial = {{ json_encode($mat) }}; openEditModal = true" title="Editar Material" class="px-2.5 py-1 rounded-lg bg-blue-500/10 hover:bg-blue-500/20 text-blue-600 dark:text-blue-400 font-medium text-xs cursor-pointer inline-flex items-center gap-1 transition-colors border border-blue-500/20">
                                        <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                        <span>Editar</span>
                                    </button>
                                    
                                    <!-- Botón Eliminar -->
                                    <button @click="activeMaterial = {{ json_encode($mat) }}; openDeleteModal = true" title="Eliminar Material" class="px-2 py-1 rounded-lg bg-rose-500/10 hover:bg-rose-500/20 text-rose-600 dark:text-rose-400 font-medium text-xs cursor-pointer inline-flex items-center gap-1 transition-colors border border-rose-500/20">
                                        <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        <span class="sr-only sm:not-sr-only">Eliminar</span>
                                    </button>
                                @endcan
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-4 py-6 text-center text-slate-400 dark:text-stone-500 text-sm">No hay materiales registrados en almacén.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>