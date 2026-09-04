<div id="editSubOrderModal" class="fixed inset-0 z-50 overflow-y-auto" style="display:none;" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-slate-900/70" onclick="closeEditSubOrderModal()"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>

        <div class="inline-block align-bottom bg-white dark:bg-stone-900 rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md w-full border border-slate-200/80 dark:border-stone-800">
            <form id="editSubOrderForm" method="POST">
                @csrf
                @method('PUT')

                <div class="px-6 py-4 border-b border-slate-100 dark:border-stone-800 flex items-center gap-3 bg-slate-50/75 dark:bg-stone-800/50">
                    <div class="w-9 h-9 rounded-xl bg-orange-50 dark:bg-orange-950/50 border border-orange-200/80 dark:border-orange-900/50 flex items-center justify-center text-orange-600 dark:text-orange-400 shrink-0">
                        <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                    </div>
                    <h3 class="text-lg font-bold text-slate-900 dark:text-stone-100 flex-1">Editar Proceso</h3>
                    <button type="button" onclick="closeEditSubOrderModal()" aria-label="Cerrar modal" class="text-slate-400 dark:text-stone-400 hover:text-slate-600 dark:hover:text-stone-200 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <div class="p-6 space-y-5">
                    <div>
                        <label for="editSubOrderProceso" class="block text-sm font-semibold text-slate-700 dark:text-stone-300 mb-1">Nombre del Proceso <span class="text-red-500">*</span></label>
                        <input type="text" name="proceso" id="editSubOrderProceso" required class="w-full px-4 py-2 bg-white dark:bg-stone-800 border border-slate-200 dark:border-stone-700 rounded-lg text-sm text-slate-700 dark:text-stone-100 outline-none focus:border-orange-500 transition-colors">
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="editSubOrderQuantity" class="block text-sm font-semibold text-slate-700 dark:text-stone-300 mb-1">Cantidad Total <span class="text-red-500">*</span></label>
                            <input type="number" name="quantity" id="editSubOrderQuantity" min="1" required class="w-full px-4 py-2 bg-white dark:bg-stone-800 border border-slate-200 dark:border-stone-700 rounded-lg text-sm text-slate-700 dark:text-stone-100 outline-none focus:border-orange-500 transition-colors">
                        </div>
                        <div>
                            <label for="editSubOrderCompleted" class="block text-sm font-semibold text-slate-700 dark:text-stone-300 mb-1">Completadas</label>
                            <input type="number" name="completed_quantity" id="editSubOrderCompleted" min="0" class="w-full px-4 py-2 bg-white dark:bg-stone-800 border border-slate-200 dark:border-stone-700 rounded-lg text-sm text-slate-700 dark:text-stone-100 outline-none focus:border-orange-500 transition-colors">
                        </div>
                    </div>

                    <div>
                        <label for="editSubOrderStatus" class="block text-sm font-semibold text-slate-700 dark:text-stone-300 mb-1">Estado</label>
                        <div class="relative">
                            <select name="status" id="editSubOrderStatus" required class="w-full appearance-none px-4 py-2 pr-9 bg-white dark:bg-stone-800 border border-slate-200 dark:border-stone-700 rounded-lg text-sm text-slate-700 dark:text-stone-100 outline-none focus:border-orange-500 transition-colors cursor-pointer">
                                <option value="pending" class="dark:bg-stone-800">Pendiente</option>
                                <option value="in_progress" class="dark:bg-stone-800">En Progreso</option>
                                <option value="completed" class="dark:bg-stone-800">Completada</option>
                            </select>
                            <svg class="w-4 h-4 text-slate-400 dark:text-stone-500 absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </div>
                    </div>

                    {{-- Operarios asignados: antes era un <select multiple> (requería Ctrl+clic).
                         Ahora son los mismos chips táctiles del modal de crear, con inicial-avatar
                         y contador en vivo. IDs con prefijo "editSubOrderOperario-{id}" para que
                         el JS que abre este modal pueda marcarlos como checked fácilmente. --}}
                    <div x-data="{ count: 0 }">
                        <div class="flex items-center justify-between mb-2">
                            <label class="block text-sm font-semibold text-slate-700 dark:text-stone-300">Operarios Asignados</label>
                            <span
                                x-show="count > 0" x-cloak
                                x-text="count + ' seleccionado' + (count === 1 ? '' : 's')"
                                class="text-[11px] font-bold text-orange-600 dark:text-orange-400 bg-orange-50 dark:bg-orange-950/40 px-2 py-0.5 rounded-full"
                            ></span>
                        </div>

                        @if($operarios->isEmpty())
                            <p class="text-xs text-slate-400 dark:text-stone-500 italic">No hay operarios disponibles para asignar.</p>
                        @else
                            <div id="editSubOrderOperariosContainer" class="flex flex-wrap gap-2 bg-slate-50 dark:bg-stone-800/50 border border-slate-100 dark:border-stone-800 rounded-xl p-3" @change="count = $el.querySelectorAll('input:checked').length">
                                @foreach($operarios as $op)
                                    <label class="cursor-pointer select-none group">
                                        <input type="checkbox" name="operarios[]" id="editSubOrderOperario-{{ $op->id }}" value="{{ $op->id }}" class="peer hidden">
                                        <span class="inline-flex items-center gap-1.5 pl-1.5 pr-3 py-1.5 rounded-full text-xs font-semibold border border-slate-200 dark:border-stone-700 text-slate-600 dark:text-stone-300 bg-white dark:bg-stone-900 peer-checked:bg-orange-600 peer-checked:text-white peer-checked:border-orange-600 group-hover:border-orange-300 dark:group-hover:border-stone-600 transition-colors">
                                            <span class="w-5 h-5 rounded-full bg-slate-100 dark:bg-stone-700 peer-checked:bg-white/20 text-slate-500 dark:text-stone-300 peer-checked:text-white flex items-center justify-center text-[10px] font-bold shrink-0">
                                                {{ strtoupper(substr($op->name ?? 'U', 0, 2)) }}
                                            </span>
                                            {{ $op->name }}
                                        </span>
                                    </label>
                                @endforeach
                            </div>
                            <p class="text-[10px] text-slate-400 dark:text-stone-500 mt-1.5">Toca uno o varios para seleccionarlos.</p>
                        @endif
                    </div>

                    <label class="flex items-start gap-3 p-3 bg-white dark:bg-stone-900 border border-slate-200 dark:border-stone-700 rounded-xl cursor-pointer has-[:checked]:border-orange-400 has-[:checked]:bg-orange-50 dark:has-[:checked]:border-orange-500 dark:has-[:checked]:bg-orange-950/30 transition-colors">
                        <input type="checkbox" name="es_ensamblaje" id="editSubOrderEsEnsamblaje" value="1" class="peer sr-only">
                        <span class="mt-0.5 w-5 h-5 shrink-0 rounded-md border-2 border-slate-300 dark:border-stone-600 flex items-center justify-center peer-checked:bg-orange-600 peer-checked:border-orange-600 transition-colors">
                            <svg class="w-3 h-3 text-white opacity-0 peer-checked:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </span>
                        <span>
                            <span class="block text-sm font-semibold text-slate-800 dark:text-stone-200">Fase final (ensamblaje)</span>
                            <span class="block text-[11px] text-slate-400 dark:text-stone-500">Esta fase determina el avance general de la orden.</span>
                        </span>
                    </label>
                </div>

                <div class="px-6 py-4 bg-slate-50/75 dark:bg-stone-800/50 border-t border-slate-100 dark:border-stone-800 flex justify-end gap-3">
                    <button type="button" onclick="closeEditSubOrderModal()" class="px-4 py-2 bg-white dark:bg-stone-800 border border-slate-200 dark:border-stone-700 text-slate-700 dark:text-stone-300 rounded-lg text-sm font-medium hover:bg-slate-50 dark:hover:bg-stone-700 transition-colors">Cancelar</button>
                    <button type="submit" class="px-5 py-2 bg-orange-600 text-white rounded-lg text-sm font-medium hover:bg-orange-700 shadow-sm shadow-orange-600/30 transition-colors">Actualizar Proceso</button>
                </div>
            </form>
        </div>
    </div>
</div>