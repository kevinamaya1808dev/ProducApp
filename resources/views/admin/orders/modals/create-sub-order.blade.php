<div id="createSubOrderModal" class="fixed inset-0 z-50 overflow-y-auto" style="display:none;" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" onclick="closeCreateSubOrderModal()"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>

        <div class="inline-block align-bottom bg-white dark:bg-stone-900 rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md w-full border border-slate-200 dark:border-stone-800">
            <form id="createSubOrderForm" action="{{ route('admin.sub-orders.store') }}" method="POST">
                @csrf
                {{-- CORRECCIÓN: antes se llamaba "order_id" y el controlador esperaba "production_order_id" --}}
                <input type="hidden" name="production_order_id" id="createSubOrderOrderId">

                <div class="px-6 py-4 border-b border-slate-100 dark:border-stone-800 flex justify-between items-center bg-slate-50 dark:bg-stone-800/50">
                    <h3 class="text-lg font-bold text-slate-900 dark:text-stone-100">Agregar Proceso</h3>
                    <button type="button" onclick="closeCreateSubOrderModal()" class="text-slate-400 dark:text-stone-400 hover:text-slate-600 dark:hover:text-stone-200 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <div class="p-6 space-y-4">
                    <div>
                        <label for="createSubOrderProceso" class="block text-sm font-semibold text-slate-700 dark:text-stone-300 mb-1">Nombre del Proceso <span class="text-red-500">*</span></label>
                        <input type="text" name="proceso" id="createSubOrderProceso" required placeholder="Ej: Corte, Ensamblaje, Pintura" class="w-full px-4 py-2 bg-white dark:bg-stone-800 border border-slate-200 dark:border-stone-700 rounded-lg text-sm text-slate-700 dark:text-stone-100 outline-none focus:border-orange-500">
                    </div>

                    <div>
                        <label for="createSubOrderQuantity" class="block text-sm font-semibold text-slate-700 dark:text-stone-300 mb-1">Cantidad Requerida (Pzas) <span class="text-red-500">*</span></label>
                        <input type="number" name="quantity" id="createSubOrderQuantity" min="1" required placeholder="Ej: 10" class="w-full px-4 py-2 bg-white dark:bg-stone-800 border border-slate-200 dark:border-stone-700 rounded-lg text-sm text-slate-700 dark:text-stone-100 outline-none focus:border-orange-500">
                    </div>

                    {{-- CORRECCIÓN + MEJORA: antes era un <select multiple> (requiere Ctrl+clic,
                         poco intuitivo). Ahora son chips que se activan con un toque, y el
                         controlador ya sí guarda esta selección en la tabla pivote. --}}
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 dark:text-stone-300 mb-2">Operarios Asignados</label>
                        @if($operarios->isEmpty())
                            <p class="text-xs text-slate-400 dark:text-stone-500 italic">No hay operarios disponibles para asignar.</p>
                        @else
                            <div class="flex flex-wrap gap-2">
                                @foreach($operarios as $op)
                                    <label class="cursor-pointer select-none">
                                        <input type="checkbox" name="operarios[]" value="{{ $op->id }}" class="peer hidden">
                                        <span class="inline-block px-3 py-1.5 rounded-full text-xs font-semibold border border-slate-200 dark:border-stone-700 text-slate-600 dark:text-stone-300 bg-white dark:bg-stone-800 peer-checked:bg-orange-600 peer-checked:text-white peer-checked:border-orange-600 transition-colors">
                                            {{ $op->name }}
                                        </span>
                                    </label>
                                @endforeach
                            </div>
                            <p class="text-[10px] text-slate-400 dark:text-stone-500 mt-1.5">Toca uno o varios para seleccionarlos.</p>
                        @endif
                    </div>

                    <div class="pt-2">
                        <label class="flex items-center gap-2 text-sm text-slate-700 dark:text-stone-300 cursor-pointer select-none">
                            <input type="checkbox" name="es_ensamblaje" value="1" class="rounded border-slate-300 text-orange-600 focus:ring-orange-500 w-4 h-4">
                            <span class="font-semibold">Fase final (ensamblaje)</span>
                        </label>
                    </div>
                </div>

                <div class="px-6 py-4 bg-slate-50 dark:bg-stone-800/50 border-t border-slate-100 dark:border-stone-800 flex justify-end gap-3">
                    <button type="button" onclick="closeCreateSubOrderModal()" class="px-4 py-2 bg-white dark:bg-stone-800 border border-slate-200 dark:border-stone-700 text-slate-700 dark:text-stone-300 rounded-lg text-sm font-medium hover:bg-slate-50 dark:hover:bg-stone-700 transition-colors">Cancelar</button>
                    <button type="submit" class="px-5 py-2 bg-orange-600 text-white rounded-lg text-sm font-medium hover:bg-orange-700 shadow-sm shadow-orange-600/30 transition-colors">Guardar Proceso</button>
                </div>
            </form>
        </div>
    </div>
</div>