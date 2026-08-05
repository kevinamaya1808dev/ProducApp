<!-- MODAL 3: HISTORIAL Y REGISTRO DE NOTAS -->
<div id="historyModal" class="fixed inset-0 z-50 hidden bg-stone-900/40 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white dark:bg-stone-900 rounded-2xl max-w-xl w-full p-6 shadow-xl border border-stone-200 dark:border-stone-800 max-h-[90vh] flex flex-col">
        <div class="flex justify-between items-center mb-4">
            <div>
                <h3 class="text-lg font-bold text-stone-800 dark:text-stone-100" id="historyModalTitle">Historial de Incidencia</h3>
                <p class="text-xs text-stone-500 dark:text-stone-400">Trazabilidad de cambios y observaciones</p>
            </div>
            <button onclick="closeModal('historyModal')" class="text-stone-400 hover:text-stone-600 dark:hover:text-stone-200 text-xl font-bold">&times;</button>
        </div>

        <!-- Agregar Nota rápida -->
        <form id="addNoteForm" method="POST" class="mb-4">
            @csrf
            <div class="flex gap-2">
                <input type="text" name="comment" required placeholder="Escribe una nota o avance..." class="flex-1 text-sm rounded-xl bg-white dark:bg-stone-800 border-stone-200 dark:border-stone-700 text-stone-800 dark:text-stone-100 placeholder-stone-400 dark:placeholder-stone-500 focus:border-orange-500 focus:ring-orange-500">
                <button type="submit" class="px-3 py-2 bg-stone-800 hover:bg-stone-900 dark:bg-stone-700 dark:hover:bg-stone-600 text-white text-xs font-medium rounded-xl transition-colors">Guardar Nota</button>
            </div>
        </form>

        <!-- Contenedor del Historial -->
        <div class="flex-1 overflow-y-auto space-y-3 pr-2" id="historyLogsContainer">
            <!-- Se llena dinámicamente mediante Javascript -->
        </div>
    </div>
</div>