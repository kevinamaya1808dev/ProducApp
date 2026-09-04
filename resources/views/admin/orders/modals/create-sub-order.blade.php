<div id="createSubOrderModal" class="fixed inset-0 z-50 overflow-y-auto" style="display:none;" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-slate-900/70" onclick="closeCreateSubOrderModal()"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>

        <div class="inline-block align-bottom bg-white dark:bg-stone-900 rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md w-full border border-slate-200/80 dark:border-stone-800">
            <form id="createSubOrderForm" action="{{ route('admin.sub-orders.store') }}" method="POST">
                @csrf
                <input type="hidden" name="production_order_id" id="createSubOrderOrderId">

                <div class="px-6 py-4 border-b border-slate-100 dark:border-stone-800 flex items-center gap-3 bg-slate-50/75 dark:bg-stone-800/50">
                    <div class="w-9 h-9 rounded-xl bg-orange-50 dark:bg-orange-950/50 border border-orange-200/80 dark:border-orange-900/50 flex items-center justify-center text-orange-600 dark:text-orange-400 shrink-0">
                        <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7h-9m3-3l3 3-3 3M4 17h9m-3 3l-3-3 3-3"></path></svg>
                    </div>
                    <h3 class="text-lg font-bold text-slate-900 dark:text-stone-100 flex-1">Agregar Proceso</h3>
                    <button type="button" onclick="closeCreateSubOrderModal()" aria-label="Cerrar modal" class="text-slate-400 dark:text-stone-400 hover:text-slate-600 dark:hover:text-stone-200 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <div class="p-6 space-y-5">
                    <div>
                        <label for="createSubOrderProceso" class="block text-sm font-semibold text-slate-700 dark:text-stone-300 mb-1">Nombre del Proceso <span class="text-red-500">*</span></label>
                        <input type="text" name="proceso" id="createSubOrderProceso" required placeholder="Ej: Corte, Ensamblaje, Pintura" class="w-full px-4 py-2 bg-white dark:bg-stone-800 border border-slate-200 dark:border-stone-700 rounded-lg text-sm text-slate-700 dark:text-stone-100 placeholder-slate-400 dark:placeholder-stone-500 outline-none focus:border-orange-500 transition-colors">
                    </div>

                    <div>
                        <label for="createSubOrderQuantity" class="block text-sm font-semibold text-slate-700 dark:text-stone-300 mb-1">Cantidad Requerida (Pzas) <span class="text-red-500">*</span></label>
                        <input type="number" name="quantity" id="createSubOrderQuantity" min="1" required placeholder="Ej: 10" class="w-full px-4 py-2 bg-white dark:bg-stone-800 border border-slate-200 dark:border-stone-700 rounded-lg text-sm text-slate-700 dark:text-stone-100 placeholder-slate-400 dark:placeholder-stone-500 outline-none focus:border-orange-500 transition-colors">
                    </div>

                    {{-- Operarios asignados: chips táctiles con inicial-avatar y contador en vivo --}}
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
                            <div class="flex flex-wrap gap-2 bg-slate-50 dark:bg-stone-800/50 border border-slate-100 dark:border-stone-800 rounded-xl p-3" @change="count = $el.querySelectorAll('input:checked').length">
                                @foreach($operarios as $op)
                                    <label class="cursor-pointer select-none group">
                                        <input type="checkbox" name="operarios[]" value="{{ $op->id }}" class="peer hidden">
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

                    {{-- Fase final (ensamblaje): tarjeta destacada en vez de un checkbox suelto --}}
                    <label class="flex items-start gap-3 p-3 bg-white dark:bg-stone-900 border border-slate-200 dark:border-stone-700 rounded-xl cursor-pointer has-[:checked]:border-orange-400 has-[:checked]:bg-orange-50 dark:has-[:checked]:border-orange-500 dark:has-[:checked]:bg-orange-950/30 transition-colors">
                        <input type="checkbox" name="es_ensamblaje" value="1" class="peer sr-only">
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
                    <button type="button" onclick="closeCreateSubOrderModal()" class="px-4 py-2 bg-white dark:bg-stone-800 border border-slate-200 dark:border-stone-700 text-slate-700 dark:text-stone-300 rounded-lg text-sm font-medium hover:bg-slate-50 dark:hover:bg-stone-700 transition-colors">Cancelar</button>
                    <button type="submit" class="px-5 py-2 bg-orange-600 text-white rounded-lg text-sm font-medium hover:bg-orange-700 shadow-sm shadow-orange-600/30 transition-colors">Guardar Proceso</button>
                </div>
            </form>
        </div>
    </div>
</div>