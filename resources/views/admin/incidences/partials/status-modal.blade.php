<!-- MODAL 2: CAMBIAR ESTADO Y PRIORIDAD -->
<div id="statusModal" class="fixed inset-0 z-50 hidden bg-stone-900/50 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white dark:bg-stone-900 rounded-2xl max-w-md w-full shadow-2xl shadow-stone-900/10 dark:shadow-black/40 border border-stone-200 dark:border-stone-800 overflow-hidden">

        <!-- Encabezado -->
        <div class="flex items-start justify-between gap-4 px-6 pt-6 pb-4 border-b border-stone-100 dark:border-stone-800">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-orange-50 dark:bg-orange-950/50 border border-orange-200/80 dark:border-orange-900/50 flex items-center justify-center text-orange-600 dark:text-orange-400 shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-stone-800 dark:text-stone-100 leading-tight">Actualizar Incidencia</h3>
                    <p class="text-xs text-stone-500 dark:text-stone-400 mt-0.5">Modifica el estado y da seguimiento</p>
                </div>
            </div>
            <button type="button" onclick="closeModal('statusModal')" class="shrink-0 w-8 h-8 flex items-center justify-center text-stone-400 hover:text-stone-600 dark:hover:text-stone-200 hover:bg-stone-100 dark:hover:bg-stone-800 rounded-lg transition-colors cursor-pointer">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>

        <div class="px-6 py-5">
            <form id="statusForm" method="POST" class="space-y-5">
                @csrf
                @method('PATCH')
                <div>
                    <label class="block text-xs font-semibold text-stone-600 dark:text-stone-400 mb-1.5">Estado actual</label>
                    <select id="modalStatusSelect" name="status" class="w-full text-sm py-3 px-3.5 rounded-xl bg-stone-50 dark:bg-stone-800 border-stone-200 dark:border-stone-700 text-stone-800 dark:text-stone-100 focus:border-orange-500 focus:ring-orange-500 focus:bg-white dark:focus:bg-stone-800 transition-colors">
                        <option value="pendiente">Pendiente</option>
                        <option value="en_proceso">En Proceso</option>
                        <option value="resuelta">Resuelta / Completada</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-stone-600 dark:text-stone-400 mb-1.5">Comentario o Nota sobre el cambio</label>
                    <textarea name="comment" rows="3" placeholder="Agrega un comentario sobre esta actualización..." class="w-full text-sm py-3 px-3.5 rounded-xl bg-stone-50 dark:bg-stone-800 border-stone-200 dark:border-stone-700 text-stone-800 dark:text-stone-100 placeholder-stone-400 dark:placeholder-stone-500 focus:border-orange-500 focus:ring-orange-500 focus:bg-white dark:focus:bg-stone-800 transition-colors resize-none"></textarea>
                </div>
                <div class="flex justify-end gap-2">
                    <button type="submit" class="w-full flex items-center justify-center gap-1.5 py-3 bg-orange-600 hover:bg-orange-700 text-white text-xs font-semibold rounded-xl shadow-sm shadow-orange-500/20 hover:shadow-md hover:shadow-orange-500/30 transition-all cursor-pointer">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                        Actualizar Estado
                    </button>
                </div>
            </form>

            @if(Auth::user()->role !== 'operario')
                <div class="my-5 flex items-center gap-3">
                    <div class="flex-1 h-px bg-stone-200 dark:bg-stone-800"></div>
                    <span class="text-[10px] font-bold uppercase tracking-wider text-stone-400 dark:text-stone-600">Solo Admin/Supervisor</span>
                    <div class="flex-1 h-px bg-stone-200 dark:bg-stone-800"></div>
                </div>

                <form id="importanceForm" method="POST" class="space-y-5">
                    @csrf
                    @method('PATCH')
                    <div>
                        <label class="block text-xs font-semibold text-stone-600 dark:text-stone-400 mb-1.5">Cambiar Prioridad</label>
                        <select id="modalImportanceSelect" name="importance" class="w-full text-sm py-3 px-3.5 rounded-xl bg-stone-50 dark:bg-stone-800 border-stone-200 dark:border-stone-700 text-stone-800 dark:text-stone-100 focus:border-orange-500 focus:ring-orange-500 focus:bg-white dark:focus:bg-stone-800 transition-colors">
                            <option value="baja">Baja</option>
                            <option value="media">Media</option>
                            <option value="alta">Alta</option>
                        </select>
                    </div>
                    <button type="submit" class="w-full flex items-center justify-center gap-1.5 py-3 bg-stone-800 hover:bg-stone-900 dark:bg-stone-700 dark:hover:bg-stone-600 text-white text-xs font-semibold rounded-xl transition-colors cursor-pointer">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                        Cambiar Prioridad
                    </button>
                </form>
            @endif
        </div>
    </div>
</div>