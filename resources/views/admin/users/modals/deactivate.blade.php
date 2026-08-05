<div id="deactivateModal" class="fixed inset-0 z-50 bg-slate-900/50 backdrop-blur-sm overflow-y-auto hidden">
    <div class="flex items-center justify-center min-h-screen px-4 text-center">
        <div class="relative w-full max-w-[22rem] p-6 text-left align-middle transition-all transform bg-white dark:bg-stone-900 shadow-2xl rounded-2xl border border-slate-200 dark:border-stone-800">

            <!-- 1. Contenedor del icono con ID -->
            <div id="deactivateModalIconContainer" class="flex items-center justify-center w-12 h-12 mb-4 rounded-full">
                <svg id="deactivateModalIcon" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <!-- El path se inyecta por JS -->
                </svg>
            </div>

            <!-- 2. Título con ID -->
            <h3 id="deactivateModalTitle" class="text-xl font-bold text-slate-900 dark:text-stone-100 mb-2"></h3>
            
            <!-- 3. Mensaje con ID -->
            <p class="text-sm text-slate-500 dark:text-stone-400 mb-6">
                <span id="deactivateModalMessage"></span>
            </p>

            <!-- Botones -->
            <div class="flex gap-3">
                <button type="button" onclick="closeDeactivateModal()" class="w-full px-4 py-2.5 text-sm font-semibold text-slate-700 dark:text-stone-300 bg-slate-50 dark:bg-stone-800 hover:bg-slate-100 dark:hover:bg-stone-700 rounded-xl transition-colors">
                    Cancelar
                </button>
                <!-- 4. Botón de confirmación con ID -->
                <button type="button" id="deactivateModalConfirmBtn" onclick="confirmDeactivate()" class="w-full px-4 py-2.5 text-sm font-semibold text-white rounded-xl transition-colors shadow-lg">
                    Confirmar
                </button>
            </div>

        </div>
    </div>
</div>