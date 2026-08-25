<div x-show="openAddStockModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div x-show="openAddStockModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 transition-opacity bg-slate-900/60 backdrop-blur-sm" @click="openAddStockModal = false"></div>

        <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>

        <div x-show="openAddStockModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block w-full max-w-sm p-6 my-8 text-left align-middle transition-all transform bg-white dark:bg-stone-800 shadow-xl rounded-2xl border border-slate-200 dark:border-stone-700">

            <div class="flex justify-between items-center mb-1">
                <h3 class="text-lg font-bold text-slate-800 dark:text-stone-200">Agregar Stock</h3>
                <button @click="openAddStockModal = false" class="text-slate-400 hover:text-slate-600 dark:hover:text-stone-200 cursor-pointer">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            <p class="text-sm text-slate-500 dark:text-stone-400 mb-4" x-text="activeMaterial.name"></p>

            <div class="flex items-center justify-between p-3 mb-4 rounded-lg bg-slate-50 dark:bg-stone-900 border border-slate-200 dark:border-stone-700">
                <span class="text-xs font-semibold text-slate-500 dark:text-stone-400">Stock actual</span>
                <span class="text-sm font-bold text-slate-800 dark:text-stone-200" x-text="activeMaterial.stock_actual + ' ' + activeMaterial.unit"></span>
            </div>

            <form :action="'/admin/almacen/material/' + activeMaterial.id + '/add-stock'" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-xs font-semibold text-slate-600 dark:text-stone-400">Cantidad que entró al almacén <span class="text-red-500">*</span></label>
                    <input type="number" step="0.01" min="0.01" name="quantity_added" required autofocus placeholder="Ej: 50" class="w-full mt-1 px-3 py-2 text-sm bg-slate-50 dark:bg-stone-900 border rounded-lg border-slate-300 dark:border-stone-700 text-slate-800 dark:text-stone-200">
                    <p class="text-[10px] text-slate-400 dark:text-stone-500 mt-1">Esta cantidad se <strong>suma</strong> al stock actual, no lo reemplaza.</p>
                </div>

                <div class="flex justify-end gap-3 mt-6">
                    <button type="button" @click="openAddStockModal = false" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 dark:bg-stone-700 dark:hover:bg-stone-600 text-slate-700 dark:text-stone-300 font-medium rounded-lg text-sm transition-colors cursor-pointer">Cancelar</button>
                    <button type="submit" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-medium rounded-lg text-sm transition-colors cursor-pointer">Agregar al Stock</button>
                </div>
            </form>
        </div>
    </div>
</div>