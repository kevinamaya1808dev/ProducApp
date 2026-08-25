<div x-show="openEditModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <!-- Fondo oscuro con efecto blur -->
        <div x-show="openEditModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 transition-opacity bg-slate-900/60 backdrop-blur-sm" @click="openEditModal = false"></div>

        <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>

        <!-- Contenedor del Modal -->
        <div x-show="openEditModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block w-full max-w-lg p-6 my-8 text-left align-middle transition-all transform bg-white dark:bg-stone-800 shadow-xl rounded-2xl border border-slate-200 dark:border-stone-700">
            
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-bold text-slate-800 dark:text-stone-200">Editar Material de Almacén</h3>
                <button @click="openEditModal = false" class="text-slate-400 hover:text-slate-600 dark:hover:text-stone-200 cursor-pointer">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <form :action="'/admin/almacen/material/' + activeMaterial.id" method="POST" class="space-y-4">
                @csrf
                @method('PUT')
                
                <div>
                    <label class="block text-xs font-semibold text-slate-600 dark:text-stone-400">Nombre del Material</label>
                    <input type="text" name="name" x-model="activeMaterial.name" required class="w-full mt-1 px-3 py-2 text-sm bg-slate-50 dark:bg-stone-900 border rounded-lg border-slate-300 dark:border-stone-700 text-slate-800 dark:text-stone-200">
                </div>
                <div class="grid grid-cols-2 gap-2">
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 dark:text-stone-400">SKU / Código</label>
                        <input type="text" name="sku" x-model="activeMaterial.sku" required class="w-full mt-1 px-3 py-2 text-sm bg-slate-50 dark:bg-stone-900 border rounded-lg border-slate-300 dark:border-stone-700 text-slate-800 dark:text-stone-200">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 dark:text-stone-400">Unidad (m, pzas, kg)</label>
                        <input type="text" name="unit" x-model="activeMaterial.unit" required class="w-full mt-1 px-3 py-2 text-sm bg-slate-50 dark:bg-stone-900 border rounded-lg border-slate-300 dark:border-stone-700 text-slate-800 dark:text-stone-200">
                    </div>
                </div>

                {{-- CORRECCIÓN: "Stock Actual" ya no es editable aquí. Se movió a su propio
                     flujo ("Agregar Stock" en la tabla), que SUMA en vez de sobreescribir.
                     Se deja visible en modo solo lectura para dar contexto sin permitir
                     que alguien lo cambie a un valor arbitrario por accidente. --}}
                <div class="grid grid-cols-2 gap-2">
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 dark:text-stone-400">Stock Actual</label>
                        <input type="text" :value="activeMaterial.stock_actual" disabled class="w-full mt-1 px-3 py-2 text-sm bg-slate-100 dark:bg-stone-800 border rounded-lg border-slate-200 dark:border-stone-700 text-slate-500 dark:text-stone-500 cursor-not-allowed">
                        <p class="text-[10px] text-slate-400 dark:text-stone-500 mt-1">Usa "Agregar Stock" en la tabla para modificarlo.</p>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 dark:text-stone-400">Stock Mínimo (Alerta)</label>
                        <input type="number" step="0.01" name="stock_minimo" x-model="activeMaterial.stock_minimo" required class="w-full mt-1 px-3 py-2 text-sm bg-slate-50 dark:bg-stone-900 border rounded-lg border-slate-300 dark:border-stone-700 text-slate-800 dark:text-stone-200">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 dark:text-stone-400">Proveedor</label>
                    <input type="text" name="proveedor" x-model="activeMaterial.proveedor" class="w-full mt-1 px-3 py-2 text-sm bg-slate-50 dark:bg-stone-900 border rounded-lg border-slate-300 dark:border-stone-700 text-slate-800 dark:text-stone-200">
                </div>

                <div class="flex justify-end gap-3 mt-6">
                    <button type="button" @click="openEditModal = false" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 dark:bg-stone-700 dark:hover:bg-stone-600 text-slate-700 dark:text-stone-300 font-medium rounded-lg text-sm transition-colors cursor-pointer">Cancelar</button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg text-sm transition-colors cursor-pointer">Actualizar Material</button>
                </div>
            </form>

        </div>
    </div>
</div>