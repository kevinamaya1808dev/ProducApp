<div id="modal-create" class="fixed inset-0 z-50 hidden bg-slate-900/50 backdrop-blur-sm overflow-y-auto items-center justify-center p-4">
    <div class="relative bg-white dark:bg-stone-900 rounded-xl shadow-xl w-full max-w-2xl transform transition-all border border-slate-200 dark:border-stone-800">
        <div class="px-6 py-4 border-b border-slate-100 dark:border-stone-800 flex items-center justify-between">
            <h3 class="text-lg font-bold text-slate-900 dark:text-stone-100">Crear Nuevo Producto</h3>
            <button onclick="closeModal('modal-create')" type="button" class="text-slate-400 dark:text-stone-400 hover:text-slate-500 dark:hover:text-stone-200 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>

        <form action="{{ route('admin.products.store') }}" method="POST">
            @csrf
            <div class="px-6 py-4 space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="col-span-2 md:col-span-1">
                        <label class="block text-xs font-semibold text-slate-700 dark:text-stone-300 mb-1">Nombre del Producto *</label>
                        <input type="text" name="name" required class="w-full px-3 py-2 bg-slate-50 dark:bg-stone-800 border border-slate-200 dark:border-stone-700 rounded-lg text-sm text-slate-700 dark:text-stone-100 placeholder-slate-400 dark:placeholder-stone-500 focus:outline-none focus:ring-2 focus:ring-orange-500">
                    </div>

                    <div class="col-span-2 md:col-span-1">
                        <label class="block text-xs font-semibold text-slate-700 dark:text-stone-300 mb-1">Código único *</label>
                        <input type="text" name="code" required class="w-full px-3 py-2 bg-slate-50 dark:bg-stone-800 border border-slate-200 dark:border-stone-700 rounded-lg text-sm text-slate-700 dark:text-stone-100 placeholder-slate-400 dark:placeholder-stone-500 focus:outline-none focus:ring-2 focus:ring-orange-500">
                    </div>

                    {{-- Dropdown Personalizado en Alpine.js --}}
                    <div class="col-span-2 md:col-span-1 relative" x-data="{ open: false, selectedId: '', selectedName: 'Seleccione una categoría...' }">
                        <label class="block text-xs font-semibold text-slate-700 dark:text-stone-300 mb-1">Categoría *</label>
                        
                        <input type="hidden" name="category_id" :value="selectedId" required>

                        <button 
                            type="button" 
                            @click="open = !open" 
                            @click.outside="open = false" 
                            class="w-full flex items-center justify-between px-3 py-2 bg-slate-50 dark:bg-stone-800 border border-slate-200 dark:border-stone-700 rounded-lg text-sm transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-orange-500 cursor-pointer"
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
                            class="absolute left-0 right-0 z-50 mt-1 max-h-48 overflow-auto bg-white dark:bg-stone-900 border border-slate-200 dark:border-stone-800 rounded-lg shadow-xl py-1 [scrollbar-width:none] [-ms-overflow-style:none] [&::-webkit-scrollbar]:hidden"
                            style="display: none;"
                        >
                            <button 
                                type="button" 
                                @click="selectedId = ''; selectedName = 'Seleccione una categoría...'; open = false;"
                                class="w-full text-left px-3 py-2 text-sm text-slate-700 dark:text-stone-300 hover:bg-orange-50 dark:hover:bg-stone-800 hover:text-orange-600 dark:hover:text-white transition-colors flex items-center justify-between cursor-pointer"
                            >
                                <span class="text-slate-400 dark:text-stone-500">Seleccione una categoría...</span>
                                <svg x-show="selectedId === ''" class="w-4 h-4 text-orange-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </button>

                            @foreach($categories as $category)
                                <button 
                                    type="button" 
                                    @click="selectedId = @js((string)$category->id); selectedName = @js($category->name); open = false;"
                                    class="w-full text-left px-3 py-2 text-sm text-slate-700 dark:text-stone-300 hover:bg-orange-50 dark:hover:bg-stone-800 hover:text-orange-600 dark:hover:text-white transition-colors flex items-center justify-between cursor-pointer"
                                >
                                    <span class="truncate">{{ $category->name }}</span>
                                    <svg x-show="selectedId == @js((string)$category->id)" class="w-4 h-4 text-orange-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </button>
                            @endforeach
                        </div>
                    </div>

                    <div class="col-span-2 md:col-span-1">
                        <label class="block text-xs font-semibold text-slate-700 dark:text-stone-300 mb-1">Costo Unitario *</label>
                        <input type="number" step="0.01" min="0" name="unit_cost" required class="w-full px-3 py-2 bg-slate-50 dark:bg-stone-800 border border-slate-200 dark:border-stone-700 rounded-lg text-sm text-slate-700 dark:text-stone-100 placeholder-slate-400 dark:placeholder-stone-500 focus:outline-none focus:ring-2 focus:ring-orange-500">
                    </div>

                    <div class="col-span-2">
                        <label class="block text-xs font-semibold text-slate-700 dark:text-stone-300 mb-1">Stock Inicial *</label>
                        <input type="number" name="stock" value="0" min="0" required class="w-full px-3 py-2 bg-slate-50 dark:bg-stone-800 border border-slate-200 dark:border-stone-700 rounded-lg text-sm text-slate-700 dark:text-stone-100 placeholder-slate-400 dark:placeholder-stone-500 focus:outline-none focus:ring-2 focus:ring-orange-500">
                    </div>

                    <div class="col-span-2">
                        <label class="block text-xs font-semibold text-slate-700 dark:text-stone-300 mb-1">Descripción</label>
                        <textarea name="description" rows="3" class="w-full px-3 py-2 bg-slate-50 dark:bg-stone-800 border border-slate-200 dark:border-stone-700 rounded-lg text-sm text-slate-700 dark:text-stone-100 placeholder-slate-400 dark:placeholder-stone-500 focus:outline-none focus:ring-2 focus:ring-orange-500 resize-none"></textarea>
                    </div>
                </div>
            </div>

            <div class="px-6 py-4 bg-slate-50 dark:bg-stone-800/50 rounded-b-xl border-t border-slate-100 dark:border-stone-800 flex justify-end gap-3">
                <button type="button" onclick="closeModal('modal-create')" class="px-4 py-2 text-xs font-semibold text-slate-600 dark:text-stone-300 bg-white dark:bg-stone-800 border border-slate-200 dark:border-stone-700 rounded-lg hover:bg-slate-50 dark:hover:bg-stone-700 transition-colors">Cancelar</button>
                <button type="submit" class="px-4 py-2 text-xs font-semibold text-white bg-orange-600 rounded-lg hover:bg-orange-700 shadow-sm shadow-orange-600/30 transition-colors">Guardar Producto</button>
            </div>
        </form>
    </div>
</div>