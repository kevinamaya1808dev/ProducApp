<div id="deactivateModal" class="fixed inset-0 z-50 bg-slate-900/50 backdrop-blur-sm overflow-y-auto hidden">
    <div class="flex items-center justify-center min-h-screen px-4 text-center">
        <div class="relative w-full max-w-[22rem] p-6 text-left align-middle transition-all transform bg-white shadow-2xl rounded-2xl">

            <!-- Icono -->
            <div class="flex items-center justify-center w-12 h-12 mb-4 bg-red-100 rounded-full">
                <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                </svg>
            </div>

            <!-- Textos -->
            <h3 class="text-xl font-bold text-slate-900 mb-2">Dar de baja</h3>
            <p class="text-sm text-slate-500 mb-6">
                ¿Dar de baja a "<span id="deactivateUserName"></span>"? No podrá recibir órdenes.
            </p>

            <!-- Botones -->
            <div class="flex gap-3">
                <button type="button" onclick="closeDeactivateModal()" class="w-full px-4 py-2.5 text-sm font-semibold text-slate-700 bg-slate-50 hover:bg-slate-100 rounded-xl transition-colors">
                    Cancelar
                </button>
                <button type="button" onclick="confirmDeactivate()" class="w-full px-4 py-2.5 text-sm font-semibold text-white bg-red-600 hover:bg-red-700 rounded-xl transition-colors">
                    Dar de baja
                </button>
            </div>

        </div>
    </div>
</div>