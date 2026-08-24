<div id="deleteSubOrderModal" class="fixed inset-0 z-50 overflow-y-auto" style="display:none;" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" onclick="closeDeleteSubOrderModal()"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>

        <div class="inline-block align-bottom bg-white dark:bg-stone-900 rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md w-full border border-slate-200 dark:border-stone-800 p-6">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-full bg-red-100 dark:bg-red-500/10 text-red-600 dark:text-red-400 flex items-center justify-center shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-slate-900 dark:text-stone-100">¿Eliminar Proceso?</h3>
                    <p class="text-sm text-slate-500 dark:text-stone-400 mt-1">Estás a punto de eliminar el proceso <strong id="deleteSubOrderName" class="text-slate-800 dark:text-stone-200"></strong>. Esta acción no se puede deshacer.</p>
                </div>
            </div>

            <form id="deleteSubOrderForm" method="POST" class="mt-6 flex justify-end gap-3">
                @csrf
                @method('DELETE')
                <button type="button" onclick="closeDeleteSubOrderModal()" class="px-4 py-2 bg-white dark:bg-stone-800 border border-slate-200 dark:border-stone-700 text-slate-700 dark:text-stone-300 rounded-lg text-sm font-medium hover:bg-slate-50 dark:hover:bg-stone-700 transition-colors">Cancelar</button>
                <button type="submit" class="px-5 py-2 bg-red-600 text-white rounded-lg text-sm font-medium hover:bg-red-700 shadow-sm shadow-red-600/30 transition-colors">Eliminar Proceso</button>
            </form>
        </div>
    </div>
</div>