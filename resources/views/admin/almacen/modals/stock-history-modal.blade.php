<div x-show="openHistoryModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div x-show="openHistoryModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 transition-opacity bg-slate-900/60 backdrop-blur-sm" @click="openHistoryModal = false"></div>

        <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>

        <div x-show="openHistoryModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block w-full max-w-md p-6 my-8 text-left align-middle transition-all transform bg-white dark:bg-stone-800 shadow-xl rounded-2xl border border-slate-200 dark:border-stone-700">

            <div class="flex justify-between items-center mb-1">
                <h3 class="text-lg font-bold text-slate-800 dark:text-stone-200">Historial de Stock</h3>
                <button @click="openHistoryModal = false" class="text-slate-400 hover:text-slate-600 dark:hover:text-stone-200 cursor-pointer">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            <p class="text-sm text-slate-500 dark:text-stone-400 mb-4" x-text="activeMaterial.name"></p>

            <div class="max-h-80 overflow-y-auto space-y-2 -mr-2 pr-2">
                <template x-if="materialHistory.length === 0">
                    <p class="text-sm text-slate-400 dark:text-stone-500 italic text-center py-6">Aún no se ha registrado ninguna entrada de stock para este material.</p>
                </template>

                <template x-for="log in materialHistory" :key="log.id">
                    <div class="flex items-center justify-between p-3 rounded-lg bg-slate-50 dark:bg-stone-900 border border-slate-200 dark:border-stone-700">
                        <div>
                            <p class="text-sm font-bold text-emerald-600 dark:text-emerald-400">
                                +<span x-text="log.quantity_added"></span> <span x-text="activeMaterial.unit"></span>
                            </p>
                            <p class="text-[11px] text-slate-400 dark:text-stone-500">
                                <span x-text="log.user ? log.user.name : 'Usuario eliminado'"></span>
                                ·
                                <span x-text="new Date(log.created_at).toLocaleString()"></span>
                            </p>
                        </div>
                        <div class="text-right">
                            <p class="text-[10px] text-slate-400 dark:text-stone-500 uppercase tracking-wide">Quedó en</p>
                            <p class="text-sm font-bold text-slate-700 dark:text-stone-200" x-text="log.stock_resultante"></p>
                        </div>
                    </div>
                </template>
            </div>

            <div class="flex justify-end mt-6">
                <button type="button" @click="openHistoryModal = false" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 dark:bg-stone-700 dark:hover:bg-stone-600 text-slate-700 dark:text-stone-300 font-medium rounded-lg text-sm transition-colors cursor-pointer">Cerrar</button>
            </div>
        </div>
    </div>
</div>