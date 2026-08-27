<div id="editRecipeModal-{{ $recipe->id }}" class="fixed inset-0 z-50 bg-slate-900/50 backdrop-blur-sm overflow-y-auto hidden">
    <div class="flex items-center justify-center min-h-screen px-4 py-8">
        <div class="relative w-full max-w-lg bg-white dark:bg-stone-900 shadow-2xl rounded-2xl border border-slate-200 dark:border-stone-800">
            <div class="flex items-start justify-between px-6 py-4 border-b border-slate-100 dark:border-stone-800">
                <div>
                    <h3 class="text-xl font-bold text-slate-900 dark:text-stone-100">Editar Receta</h3>
                    <p class="text-sm text-slate-500 dark:text-stone-400 mt-1">REC-00{{ $recipe->id }}</p>
                </div>
                <button type="button" onclick="closeModal('editRecipeModal-{{ $recipe->id }}')" class="text-slate-400 dark:text-stone-400 hover:text-slate-600 dark:hover:text-stone-200 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            <form action="{{ route('admin.recipes.update', $recipe->id) }}" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="form_source" value="editRecipeModal-{{ $recipe->id }}">
                <div class="px-6 py-6 space-y-5">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 dark:text-stone-300 mb-2">Nombre de la receta <span class="text-red-500">*</span></label>
                        <input type="text" name="name" required value="{{ old('name', $recipe->name) }}" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 dark:border-stone-700 bg-white dark:bg-stone-800 focus:border-orange-500 dark:focus:border-orange-500 focus:ring-1 focus:ring-orange-500 outline-none text-slate-700 dark:text-stone-100">
                        @error('name') <p class="text-xs text-red-600 dark:text-red-400 mt-1">{{ $message }}</p> @enderror
                    </div>

                    @php
                        $currentProductId = old('product_id', $recipe->product_id);
                        $selectedProduct = $products->firstWhere('id', $currentProductId) ?? $products->firstWhere('id', (int) $currentProductId);
                        $initialProductName = $selectedProduct ? $selectedProduct->name : 'Selecciona un producto';
                        $initialProductId = $selectedProduct ? (string) $selectedProduct->id : '';
                    @endphp

                    {{-- Dropdown Personalizado en Alpine.js --}}
                    <div 
                        class="relative" 
                        x-data="{ 
                            open: false, 
                            selectedId: @js($initialProductId), 
                            selectedName: @js($initialProductName) 
                        }"
                    >
                        <label class="block text-sm font-semibold text-slate-700 dark:text-stone-300 mb-2">Producto asociado <span class="text-red-500">*</span></label>
                        
                        <input type="hidden" name="product_id" :value="selectedId" required>

                        <button 
                            type="button" 
                            @click="open = !open" 
                            @click.outside="open = false" 
                            class="w-full flex items-center justify-between px-4 py-2.5 rounded-xl border border-slate-200 dark:border-stone-700 bg-white dark:bg-stone-800 text-sm transition-all duration-200 focus:outline-none focus:ring-1 focus:ring-orange-500 focus:border-orange-500 cursor-pointer"
                            :class="selectedId ? 'text-slate-700 dark:text-stone-100' : 'text-slate-400 dark:text-stone-500'"
                        >
                            <span x-text="selectedName" class="truncate"></span>
                            <svg class="w-4 h-4 text-slate-400 dark:text-stone-500 transition-transform duration-200 shrink-0" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>

                        <div 
                            x-show="open" 
                            x-cloak
                            x-transition:enter="transition ease-out duration-100"
                            x-transition:enter-start="transform opacity-0 scale-95"
                            x-transition:enter-end="transform opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-75"
                            x-transition:leave-start="transform opacity-100 scale-100"
                            x-transition:leave-end="transform opacity-0 scale-95"
                            class="absolute left-0 right-0 z-50 mt-1 max-h-48 overflow-auto bg-white dark:bg-stone-900 border border-slate-200 dark:border-stone-800 rounded-xl shadow-xl py-1 [scrollbar-width:none] [-ms-overflow-style:none] [&::-webkit-scrollbar]:hidden"
                            style="display: none;"
                        >
                            <button 
                                type="button" 
                                @click="selectedId = ''; selectedName = 'Selecciona un producto'; open = false;"
                                class="w-full text-left px-4 py-2 text-sm text-slate-700 dark:text-stone-300 hover:bg-orange-50 dark:hover:bg-stone-800 hover:text-orange-600 dark:hover:text-white transition-colors flex items-center justify-between cursor-pointer"
                            >
                                <span class="text-slate-400 dark:text-stone-500">Selecciona un producto</span>
                                <svg x-show="selectedId === ''" class="w-4 h-4 text-orange-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </button>

                            @foreach($products as $product)
                                <button 
                                    type="button" 
                                    @click="selectedId = @js((string)$product->id); selectedName = @js($product->name); open = false;"
                                    class="w-full text-left px-4 py-2 text-sm text-slate-700 dark:text-stone-300 hover:bg-orange-50 dark:hover:bg-stone-800 hover:text-orange-600 dark:hover:text-white transition-colors flex items-center justify-between cursor-pointer"
                                >
                                    <span class="truncate">{{ $product->name }}</span>
                                    <svg x-show="selectedId == @js((string)$product->id)" class="w-4 h-4 text-orange-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </button>
                            @endforeach
                        </div>

                        @error('product_id') <p class="text-xs text-red-600 dark:text-red-400 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 dark:text-stone-300 mb-2">Instrucciones / Procedimiento</label>
                        <textarea name="instructions" rows="4" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 dark:border-stone-700 bg-white dark:bg-stone-800 focus:border-orange-500 dark:focus:border-orange-500 focus:ring-1 focus:ring-orange-500 outline-none text-slate-700 dark:text-stone-100 resize-none">{{ old('instructions', $recipe->instructions) }}</textarea>
                    </div>
                </div>
                <div class="px-6 py-4 border-t border-slate-100 dark:border-stone-800 flex items-center justify-end gap-3 bg-slate-50 dark:bg-stone-800/50 rounded-b-2xl">
                    <button type="button" onclick="closeModal('editRecipeModal-{{ $recipe->id }}')" class="px-5 py-2.5 text-sm font-semibold text-slate-700 dark:text-stone-300 bg-white dark:bg-stone-800 border border-slate-300 dark:border-stone-700 rounded-lg hover:bg-slate-50 dark:hover:bg-stone-700 transition-colors">Cancelar</button>
                    <button type="submit" class="px-5 py-2.5 text-sm font-semibold text-white bg-orange-600 rounded-lg hover:bg-orange-700 shadow-sm shadow-orange-600/30 transition-colors">Guardar Cambios</button>
                </div>
            </form>
        </div>
    </div>
</div>