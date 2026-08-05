<!-- MODAL 4: ELIMINAR INCIDENCIA -->
<div id="deleteModal" class="fixed inset-0 z-50 hidden bg-stone-900/40 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white dark:bg-stone-900 rounded-2xl max-w-md w-full p-6 shadow-xl border border-stone-200 dark:border-stone-800">
        <!-- Encabezado con icono de advertencia -->
        <div class="flex items-center gap-3 mb-4 text-rose-600 dark:text-rose-400">
            <div class="p-2.5 bg-rose-100 dark:bg-rose-950/60 rounded-xl">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
            </div>
            <div>
                <h3 class="text-lg font-bold text-stone-800 dark:text-stone-100">Eliminar Incidencia</h3>
                <p class="text-xs text-stone-500 dark:text-stone-400">Esta acción no se puede deshacer.</p>
            </div>
        </div>

        <!-- Información de la incidencia a eliminar -->
        <div class="bg-stone-50 dark:bg-stone-800/50 border border-stone-200 dark:border-stone-700 rounded-xl p-3.5 mb-5">
            <span id="deleteIncidenceOrder" class="text-xs font-mono font-bold px-2 py-0.5 rounded bg-stone-200 dark:bg-stone-700 text-stone-700 dark:text-stone-200"></span>
            <p id="deleteIncidenceTitle" class="text-sm font-medium text-stone-800 dark:text-stone-100 mt-1.5"></p>
        </div>

        <!-- Formulario de eliminación -->
        <form id="deleteForm" method="POST">
            @csrf
            @method('DELETE')
            
            <p class="text-xs text-stone-600 dark:text-stone-300 mb-6">
                ¿Estás seguro de que deseas eliminar esta incidencia y todo su historial asociado?
            </p>

            <div class="flex justify-end gap-2">
                <button type="button" onclick="closeModal('deleteModal')" class="px-4 py-2 bg-stone-100 hover:bg-stone-200 dark:bg-stone-800 dark:hover:bg-stone-700 text-stone-700 dark:text-stone-300 text-xs font-medium rounded-xl transition-colors">
                    Cancelar
                </button>
                <button type="submit" class="px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white text-xs font-medium rounded-xl shadow-sm transition-colors">
                    Sí, eliminar
                </button>
            </div>
        </form>
    </div>
</div>