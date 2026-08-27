<div id="addComponentModal" class="fixed inset-0 z-50 bg-slate-900/50 backdrop-blur-sm overflow-y-auto hidden">
    <div class="flex items-center justify-center min-h-screen px-4 py-8">
        <div class="relative w-full max-w-lg bg-white dark:bg-stone-900 shadow-2xl rounded-2xl border border-slate-200 dark:border-stone-800">
            <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100 dark:border-stone-800">
                <h3 class="text-xl font-bold text-slate-900 dark:text-stone-100">Agregar Componente</h3>
                <button type="button" onclick="closeModal('addComponentModal')" class="text-slate-400 dark:text-stone-400 hover:text-slate-600 dark:hover:text-stone-200 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            <form action="{{ route('admin.recipes.components.store', $recipe->id) }}" method="POST">
                @csrf
                <input type="hidden" name="form_source" value="addComponentModal">
                <div class="px-6 py-6 space-y-5">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 dark:text-stone-300 mb-2">Nombre <span class="text-red-500">*</span></label>
                        <input type="text" name="name" required placeholder="Ej: Tela denim 12oz" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 dark:border-stone-700 bg-white dark:bg-stone-800 focus:border-emerald-500 dark:focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 outline-none text-slate-700 dark:text-stone-100 placeholder-slate-400 dark:placeholder-stone-500">
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        @php
                            $currentTypeId = old('component_type_id', '');
                            $selectedType = $componentTypes->firstWhere('id', $currentTypeId) ?? $componentTypes->firstWhere('id', (int) $currentTypeId);
                            $initialTypeName = $selectedType ? $selectedType->name : 'Sin tipo';
                            $initialTypeId = $selectedType ? (string) $selectedType->id : '';
                        @endphp

                        {{-- Dropdown Personalizado: Tipo de Componente --}}
                        <div 
                            class="relative" 
                            x-data="{ 
                                open: false, 
                                selectedId: @js($initialTypeId), 
                                selectedName: @js($initialTypeName) 
                            }"
                        >
                            <label class="block text-sm font-semibold text-slate-700 dark:text-stone-300 mb-2">Tipo</label>
                            
                            <input type="hidden" name="component_type_id" :value="selectedId">

                            <button 
                                type="button" 
                                @click="open = !open" 
                                @click.outside="open = false" 
                                class="w-full flex items-center justify-between px-4 py-2.5 rounded-xl border border-slate-200 dark:border-stone-700 bg-white dark:bg-stone-800 text-sm transition-all duration-200 focus:outline-none focus:ring-1 focus:ring-emerald-500 focus:border-emerald-500 cursor-pointer"
                                :class="selectedId ? 'text-slate-700 dark:text-stone-100' : 'text-slate-700 dark:text-stone-300'"
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
                                    @click="selectedId = ''; selectedName = 'Sin tipo'; open = false;"
                                    class="w-full text-left px-4 py-2 text-sm text-slate-700 dark:text-stone-300 hover:bg-emerald-50 dark:hover:bg-stone-800 hover:text-emerald-600 dark:hover:text-white transition-colors flex items-center justify-between cursor-pointer"
                                >
                                    <span class="text-slate-500 dark:text-stone-400">Sin tipo</span>
                                    <svg x-show="selectedId === ''" class="w-4 h-4 text-emerald-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </button>

                                @foreach($componentTypes as $type)
                                    <button 
                                        type="button" 
                                        @click="selectedId = @js((string)$type->id); selectedName = @js($type->name); open = false;"
                                        class="w-full text-left px-4 py-2 text-sm text-slate-700 dark:text-stone-300 hover:bg-emerald-50 dark:hover:bg-stone-800 hover:text-emerald-600 dark:hover:text-white transition-colors flex items-center justify-between cursor-pointer"
                                    >
                                        <span class="truncate">{{ $type->name }}</span>
                                        <svg x-show="selectedId == @js((string)$type->id)" class="w-4 h-4 text-emerald-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    </button>
                                @endforeach
                            </div>

                            <a href="{{ route('admin.component-types.index') }}" target="_blank" class="text-[11px] text-emerald-600 dark:text-emerald-400 hover:text-emerald-700 dark:hover:text-emerald-300 font-medium mt-1.5 inline-block">
                                + Gestionar tipos de componente
                            </a>
                        </div>

                        @php
                            $currentUnit = old('base_unit', 'pzas');
                        @endphp

                        {{-- Dropdown Personalizado: Unidad --}}
                        <div 
                            class="relative" 
                            x-data="{ 
                                open: false, 
                                selectedUnit: @js($currentUnit) 
                            }"
                        >
                            <label class="block text-sm font-semibold text-slate-700 dark:text-stone-300 mb-2">Unidad</label>
                            
                            <input type="hidden" name="base_unit" :value="selectedUnit">

                            <button 
                                type="button" 
                                @click="open = !open" 
                                @click.outside="open = false" 
                                class="w-full flex items-center justify-between px-4 py-2.5 rounded-xl border border-slate-200 dark:border-stone-700 bg-white dark:bg-stone-800 text-sm text-slate-700 dark:text-stone-100 transition-all duration-200 focus:outline-none focus:ring-1 focus:ring-emerald-500 focus:border-emerald-500 cursor-pointer"
                            >
                                <span x-text="selectedUnit" class="truncate"></span>
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
                                @foreach(['pzas','m','m²','cm','kg','g','l','ml','par'] as $unit)
                                    <button 
                                        type="button" 
                                        @click="selectedUnit = @js($unit); open = false;"
                                        class="w-full text-left px-4 py-2 text-sm text-slate-700 dark:text-stone-300 hover:bg-emerald-50 dark:hover:bg-stone-800 hover:text-emerald-600 dark:hover:text-white transition-colors flex items-center justify-between cursor-pointer"
                                    >
                                        <span class="truncate">{{ $unit }}</span>
                                        <svg x-show="selectedUnit === @js($unit)" class="w-4 h-4 text-emerald-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 dark:text-stone-300 mb-2">Cantidad <span class="text-red-500">*</span></label>
                        <input type="number" step="0.01" min="0.01" name="quantity" required placeholder="0" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 dark:border-stone-700 bg-white dark:bg-stone-800 focus:border-emerald-500 dark:focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 outline-none text-slate-700 dark:text-stone-100 placeholder-slate-400 dark:placeholder-stone-500">
                    </div>
                </div>
                <div class="px-6 py-4 border-t border-slate-100 dark:border-stone-800 flex items-center justify-end gap-3 bg-slate-50 dark:bg-stone-800/50 rounded-b-2xl">
                    <button type="button" onclick="closeModal('addComponentModal')" class="px-5 py-2.5 text-sm font-semibold text-slate-700 dark:text-stone-300 bg-white dark:bg-stone-800 border border-slate-300 dark:border-stone-700 rounded-lg hover:bg-slate-50 dark:hover:bg-stone-700 transition-colors">Cancelar</button>
                    <button type="submit" class="px-5 py-2.5 text-sm font-semibold text-white bg-emerald-600 rounded-lg hover:bg-emerald-700 shadow-sm shadow-emerald-600/30 transition-colors">Agregar</button>
                </div>
            </form>
        </div>
    </div>
</div>