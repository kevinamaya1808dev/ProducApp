<div x-show="openRecipeModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
    <div class="flex min-h-full items-center justify-center p-3 sm:p-4">
        <div x-show="openRecipeModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 transition-opacity bg-black/70 backdrop-blur-sm" @click="openRecipeModal = false"></div>

        <div x-show="openRecipeModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="relative transform w-full max-w-lg p-4 sm:p-6 my-4 sm:my-8 text-left transition-all bg-white dark:bg-stone-900 shadow-xl rounded-2xl border border-slate-200 dark:border-stone-800">
            
            <div class="flex justify-between items-center gap-3 mb-4">
                <h3 class="text-base sm:text-lg font-bold text-slate-800 dark:text-white">Vincular Receta (Componente x Producto)</h3>
                <button @click="openRecipeModal = false" class="shrink-0 text-slate-400 hover:text-slate-600 dark:text-stone-500 dark:hover:text-white cursor-pointer">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <form action="{{ route('admin.almacen.recipe.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-semibold text-slate-600 dark:text-stone-400">Seleccionar Producto</label>
                    <select name="product_id" required class="w-full mt-1 px-3 py-2 text-sm bg-slate-50 dark:bg-stone-950 border rounded-lg border-slate-300 dark:border-stone-700 text-slate-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-orange-500/50 focus:border-orange-500">
                        @foreach($products as $prod)
                            <option value="{{ $prod->id }}">{{ $prod->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 dark:text-stone-400">Material / Insumo Requerido</label>
                    <select name="material_id" required class="w-full mt-1 px-3 py-2 text-sm bg-slate-50 dark:bg-stone-950 border rounded-lg border-slate-300 dark:border-stone-700 text-slate-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-orange-500/50 focus:border-orange-500">
                        @foreach($materials as $mat)
                            <option value="{{ $mat->id }}">{{ $mat->name }} ({{ $mat->unit }})</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 dark:text-stone-400">Cantidad por Unidad de Producto</label>
                    <input type="number" step="0.01" name="quantity_required" required placeholder="Ej. 1.5" class="w-full mt-1 px-3 py-2 text-sm bg-slate-50 dark:bg-stone-950 border rounded-lg border-slate-300 dark:border-stone-700 text-slate-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-orange-500/50 focus:border-orange-500">
                </div>

                <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-2 sm:gap-3 mt-6">
                    <button type="button" @click="openRecipeModal = false" class="w-full sm:w-auto px-4 py-2.5 sm:py-2 bg-slate-100 hover:bg-slate-200 dark:bg-stone-800 dark:hover:bg-stone-700 dark:border dark:border-stone-700 text-slate-700 dark:text-stone-200 font-medium rounded-lg text-sm transition-colors cursor-pointer">Cancelar</button>
                    <button type="submit" class="w-full sm:w-auto px-4 py-2.5 sm:py-2 bg-orange-600 hover:bg-orange-700 text-white font-bold rounded-lg text-sm transition-colors cursor-pointer">Vincular a Receta</button>
                </div>
            </form>

        </div>
    </div>
</div>