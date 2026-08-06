<!-- MODAL 3: HISTORIAL Y REGISTRO DE NOTAS -->
<div id="historyModal" class="fixed inset-0 z-50 hidden bg-stone-900/50 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white dark:bg-stone-900 rounded-2xl max-w-xl w-full shadow-2xl shadow-stone-900/10 dark:shadow-black/40 border border-stone-200 dark:border-stone-800 max-h-[90vh] flex flex-col overflow-hidden">

        <!-- Encabezado -->
        <div class="flex items-start justify-between gap-4 px-6 pt-6 pb-4 border-b border-stone-100 dark:border-stone-800 shrink-0">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-orange-50 dark:bg-orange-950/50 border border-orange-200/80 dark:border-orange-900/50 flex items-center justify-center text-orange-600 dark:text-orange-400 shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-stone-800 dark:text-stone-100 leading-tight" id="historyModalTitle">Historial de Incidencia</h3>
                    <p class="text-xs text-stone-500 dark:text-stone-400 mt-0.5">Trazabilidad de cambios y observaciones</p>
                </div>
            </div>
            <button type="button" onclick="closeModal('historyModal')" class="shrink-0 w-8 h-8 flex items-center justify-center text-stone-400 hover:text-stone-600 dark:hover:text-stone-200 hover:bg-stone-100 dark:hover:bg-stone-800 rounded-lg transition-colors cursor-pointer">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>

        <div class="px-6 pt-4 pb-2 shrink-0">
            <!-- Agregar Nota rápida -->
            <form id="addNoteForm" method="POST">
                @csrf
                <div class="flex gap-2">
                    <input type="text" name="comment" required placeholder="Escribe una nota o avance..." class="flex-1 text-sm rounded-xl bg-stone-50 dark:bg-stone-800 border-stone-200 dark:border-stone-700 text-stone-800 dark:text-stone-100 placeholder-stone-400 dark:placeholder-stone-500 focus:border-orange-500 focus:ring-orange-500 focus:bg-white dark:focus:bg-stone-800 transition-colors">
                    <button type="submit" class="shrink-0 flex items-center gap-1.5 px-4 py-2 bg-stone-800 hover:bg-stone-900 dark:bg-stone-700 dark:hover:bg-stone-600 text-white text-xs font-semibold rounded-xl transition-colors cursor-pointer">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
                        Guardar
                    </button>
                </div>
            </form>
        </div>

        <!-- Contenedor del Historial -->
        <div class="flex-1 overflow-y-auto px-6 pb-6 pt-2 space-y-3" id="historyLogsContainer">
            <!-- Se llena dinámicamente mediante Javascript -->
        </div>
    </div>
</div>