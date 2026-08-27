<div class="bg-white dark:bg-stone-900 p-6 rounded-xl shadow-sm border border-slate-200 dark:border-stone-800">
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-md font-bold text-slate-800 dark:text-white">Directorio de Proveedores</h3>
        <span class="text-xs text-slate-400 dark:text-stone-500" x-text="`${filteredProveedores.length} de ${proveedores.length}`"></span>
    </div>
    <div class="w-full overflow-x-auto">
        <table class="w-full text-left text-sm text-slate-600 dark:text-stone-300 align-middle">
            <thead class="bg-slate-50 dark:bg-stone-950/60 text-xs uppercase font-bold text-slate-500 dark:text-stone-500 border-b border-slate-200 dark:border-stone-800">
                <tr>
                    <th class="px-3 py-3">Empresa / Nombre</th>
                    <th class="px-3 py-3">Contacto</th>
                    <th class="px-3 py-3">Teléfono</th>
                    <th class="px-3 py-3">Correo Electrónico</th>
                    <th class="px-3 py-3">Dirección</th>
                    <th class="px-3 py-3 text-right">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200 dark:divide-stone-800">
                <template x-for="prov in filteredProveedores" :key="prov.id">
                    <tr class="hover:bg-slate-50/50 dark:hover:bg-stone-800/40 transition-colors">
                        <td class="px-3 py-3 whitespace-nowrap">
                            <div class="flex items-center gap-2.5">
                                <div class="w-7 h-7 shrink-0 rounded-full bg-orange-500/10 text-orange-600 dark:text-orange-400 flex items-center justify-center text-[11px] font-bold uppercase" x-text="prov.nombre.charAt(0)"></div>
                                <span class="font-semibold text-slate-800 dark:text-white" x-text="prov.nombre"></span>
                            </div>
                        </td>
                        <td class="px-3 py-3 text-xs text-slate-600 dark:text-stone-400 whitespace-nowrap" x-text="prov.contacto_nombre || '—'"></td>
                        <td class="px-3 py-3 text-xs font-mono whitespace-nowrap">
                            <template x-if="prov.telefono">
                                <a :href="'tel:' + prov.telefono" class="text-slate-600 dark:text-stone-300 hover:text-orange-600 dark:hover:text-orange-400 transition-colors" x-text="prov.telefono"></a>
                            </template>
                            <template x-if="!prov.telefono"><span class="text-slate-400 dark:text-stone-500">—</span></template>
                        </td>
                        <td class="px-3 py-3 text-xs whitespace-nowrap">
                            <template x-if="prov.email">
                                <a :href="'mailto:' + prov.email" class="text-slate-600 dark:text-stone-300 hover:text-orange-600 dark:hover:text-orange-400 transition-colors" x-text="prov.email"></a>
                            </template>
                            <template x-if="!prov.email"><span class="text-slate-400 dark:text-stone-500">—</span></template>
                        </td>
                        <td class="px-3 py-3 text-xs max-w-[200px] truncate text-slate-500 dark:text-stone-400" :title="prov.direccion" x-text="prov.direccion || '—'"></td>
                        <td class="px-3 py-3 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <button @click="activeProveedor = prov; openEditModal = true" title="Editar Proveedor" class="w-8 h-8 flex items-center justify-center rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-600 dark:bg-stone-800 dark:hover:bg-stone-700 dark:border dark:border-stone-700 dark:text-stone-300 dark:hover:text-white transition-colors cursor-pointer">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                </button>
                                <button @click="activeProveedor = prov; openDeleteModal = true" title="Eliminar Proveedor" class="w-8 h-8 flex items-center justify-center rounded-lg bg-slate-100 hover:bg-rose-100 text-slate-600 hover:text-rose-600 dark:bg-stone-800 dark:hover:bg-rose-500/10 dark:border dark:border-stone-700 dark:hover:border-rose-500/30 dark:text-stone-300 dark:hover:text-rose-400 transition-colors cursor-pointer">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                </template>
                <tr x-show="filteredProveedores.length === 0" x-cloak>
                    <td colspan="6" class="px-4 py-6 text-center text-slate-400 dark:text-stone-500 text-sm">
                        <span x-show="proveedores.length === 0">No hay proveedores registrados.</span>
                        <span x-show="proveedores.length > 0">No se encontraron proveedores con esa búsqueda.</span>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>