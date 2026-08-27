<div x-show="openMaterialModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div x-show="openMaterialModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 transition-opacity bg-black/70 backdrop-blur-sm" @click="openMaterialModal = false"></div>

        <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>

        <div x-show="openMaterialModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block w-full max-w-lg p-6 my-8 text-left align-middle transition-all transform bg-white dark:bg-stone-900 shadow-xl rounded-2xl border border-slate-200 dark:border-stone-800">
            
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-bold text-slate-800 dark:text-white">Registrar Material de Proveedor</h3>
                <button @click="openMaterialModal = false" class="text-slate-400 hover:text-slate-600 dark:text-stone-500 dark:hover:text-white cursor-pointer">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <form action="{{ route('admin.almacen.material.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-semibold text-slate-600 dark:text-stone-400">Nombre del Material</label>
                    <input type="text" name="name" required class="w-full mt-1 px-3 py-2 text-sm bg-slate-50 dark:bg-stone-950 border rounded-lg border-slate-300 dark:border-stone-700 text-slate-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-orange-500/50 focus:border-orange-500">
                </div>
                <div class="grid grid-cols-2 gap-2">
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 dark:text-stone-400">SKU / Código</label>
                        <input type="text" name="sku" required class="w-full mt-1 px-3 py-2 text-sm bg-slate-50 dark:bg-stone-950 border rounded-lg border-slate-300 dark:border-stone-700 text-slate-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-orange-500/50 focus:border-orange-500">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 dark:text-stone-400">Unidad (m, pzas, kg)</label>
                        <input type="text" name="unit" required class="w-full mt-1 px-3 py-2 text-sm bg-slate-50 dark:bg-stone-950 border rounded-lg border-slate-300 dark:border-stone-700 text-slate-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-orange-500/50 focus:border-orange-500">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-2">
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 dark:text-stone-400">Stock Inicial</label>
                        <input type="number" step="0.01" name="stock_actual" required class="w-full mt-1 px-3 py-2 text-sm bg-slate-50 dark:bg-stone-950 border rounded-lg border-slate-300 dark:border-stone-700 text-slate-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-orange-500/50 focus:border-orange-500">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 dark:text-stone-400">Stock Mínimo (Alerta)</label>
                        <input type="number" step="0.01" name="stock_minimo" required class="w-full mt-1 px-3 py-2 text-sm bg-slate-50 dark:bg-stone-950 border rounded-lg border-slate-300 dark:border-stone-700 text-slate-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-orange-500/50 focus:border-orange-500">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 dark:text-stone-400">Proveedor</label>
                    <input type="text" name="proveedor" class="w-full mt-1 px-3 py-2 text-sm bg-slate-50 dark:bg-stone-950 border rounded-lg border-slate-300 dark:border-stone-700 text-slate-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-orange-500/50 focus:border-orange-500">
                </div>

                <div class="flex justify-end gap-3 mt-6">
                    <button type="button" @click="openMaterialModal = false" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 dark:bg-stone-800 dark:hover:bg-stone-700 dark:border dark:border-stone-700 text-slate-700 dark:text-stone-200 font-medium rounded-lg text-sm transition-colors cursor-pointer">Cancelar</button>
                    <button type="submit" class="px-4 py-2 bg-orange-600 hover:bg-orange-700 text-white font-bold rounded-lg text-sm transition-colors cursor-pointer">Guardar Material</button>
                </div>
            </form>

        </div>
    </div>
</div>