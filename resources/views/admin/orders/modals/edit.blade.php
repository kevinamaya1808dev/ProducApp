<div id="editOrderModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" onclick="closeEditModal()"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>

        <div class="inline-block align-bottom bg-white dark:bg-stone-900 rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-xl w-full border border-slate-200 dark:border-stone-800">
            <form id="editOrderForm" method="POST">
                @csrf
                @method('PUT')
                <div class="px-6 py-4 border-b border-slate-100 dark:border-stone-800 flex justify-between items-center bg-slate-50 dark:bg-stone-800/50">
                    <h3 class="text-lg font-bold text-slate-900 dark:text-stone-100">Editar Orden de Producción</h3>
                    <button type="button" onclick="closeEditModal()" class="text-slate-400 dark:text-stone-400 hover:text-slate-600 dark:hover:text-stone-200 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <div class="p-6 space-y-4 max-h-[70vh] overflow-y-auto">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 dark:text-stone-300 mb-1">Número de Orden <span class="text-red-500">*</span></label>
                        <input type="text" id="editOrderNumber" name="order_number" required class="w-full px-4 py-2 bg-white dark:bg-stone-800 border border-slate-200 dark:border-stone-700 rounded-lg text-sm text-slate-700 dark:text-stone-100 outline-none focus:border-orange-500">
                    </div>

                    {{-- Dropdown Personalizado: Producto
                         FIX ADICIONAL: se añadió @closemodal.window="open = false" para que el
                         panel teletransportado (x-teleport="body") se cierre cuando el modal se
                         cierra. Al vivir fuera del modal, cerrar el modal (display:none) no lo
                         ocultaba por sí solo; ahora escucha el evento global 'closemodal' que debe
                         dispararse dentro de closeEditModal() con:
                         window.dispatchEvent(new Event('closemodal')) --}}
                    <div 
                        class="relative" 
                        x-data="{ 
                            open: false, 
                            selectedId: '', 
                            selectedName: 'Seleccione un producto...',
                            panelStyle: '',
                            options: {
                                '': 'Seleccione un producto...',
                                @foreach($products as $product)
                                    @js((string)$product->id): @js($product->name),
                                @endforeach
                            },
                            sync() {
                                this.selectedId = this.$refs.hiddenInput.value || '';
                                this.selectedName = this.options[this.selectedId] || 'Seleccione un producto...';
                            },
                            toggle() {
                                this.open = !this.open;
                                if (this.open) {
                                    this.$nextTick(() => {
                                        const rect = this.$refs.trigger.getBoundingClientRect();
                                        this.panelStyle = `top:${rect.bottom + 4}px; left:${rect.left}px; width:${rect.width}px;`;
                                    });
                                }
                            }
                        }"
                        x-init="
                            sync();
                            $refs.hiddenInput.addEventListener('change', () => sync());
                            $refs.hiddenInput.addEventListener('input', () => sync());
                        "
                        @scroll.window.capture="open = false"
                        @closemodal.window="open = false"
                    >
                        <label class="block text-sm font-semibold text-slate-700 dark:text-stone-300 mb-1">Producto a fabricar <span class="text-red-500">*</span></label>
                        
                        <input type="hidden" id="editProductId" name="product_id" x-ref="hiddenInput" required>

                        <button 
                            type="button" 
                            x-ref="trigger"
                            @click="toggle()" 
                            @click.outside="open = false" 
                            class="w-full flex items-center justify-between px-4 py-2 bg-white dark:bg-stone-800 border border-slate-200 dark:border-stone-700 rounded-lg text-sm text-slate-700 dark:text-stone-100 outline-none focus:border-orange-500 cursor-pointer"
                        >
                            <span x-text="selectedName" class="truncate" :class="selectedId ? 'text-slate-700 dark:text-stone-100' : 'text-slate-400 dark:text-stone-500'"></span>
                            <svg class="w-4 h-4 text-slate-400 dark:text-stone-500 transition-transform duration-200 shrink-0" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>

                        <template x-teleport="body">
                            <div 
                                x-show="open" 
                                x-cloak
                                :style="panelStyle"
                                x-transition:enter="transition ease-out duration-100"
                                x-transition:enter-start="transform opacity-0 scale-95"
                                x-transition:enter-end="transform opacity-100 scale-100"
                                x-transition:leave="transition ease-in duration-75"
                                x-transition:leave-start="transform opacity-100 scale-100"
                                x-transition:leave-end="transform opacity-0 scale-95"
                                class="fixed z-[9999] max-h-64 overflow-auto bg-white dark:bg-stone-800 border border-slate-200 dark:border-stone-700 rounded-lg shadow-2xl py-1 [scrollbar-width:none] [-ms-overflow-style:none] [&::-webkit-scrollbar]:hidden"
                                style="display: none;"
                            >
                                <button 
                                    type="button" 
                                    @click="selectedId = ''; $refs.hiddenInput.value = ''; sync(); open = false;"
                                    class="w-full text-left px-4 py-2 text-sm text-slate-700 dark:text-stone-300 hover:bg-orange-50 dark:hover:bg-stone-700 hover:text-orange-600 dark:hover:text-white transition-colors flex items-center justify-between cursor-pointer"
                                >
                                    <span class="text-slate-400 dark:text-stone-500">Seleccione un producto...</span>
                                    <svg x-show="selectedId === ''" class="w-4 h-4 text-orange-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </button>

                                @foreach($products as $product)
                                    <button 
                                        type="button" 
                                        @click="selectedId = @js((string)$product->id); $refs.hiddenInput.value = selectedId; sync(); open = false;"
                                        class="w-full text-left px-4 py-2 text-sm text-slate-700 dark:text-stone-300 hover:bg-orange-50 dark:hover:bg-stone-700 hover:text-orange-600 dark:hover:text-white transition-colors flex items-center justify-between cursor-pointer"
                                    >
                                        <span class="truncate">{{ $product->name }}</span>
                                        <svg x-show="selectedId == @js((string)$product->id)" class="w-4 h-4 text-orange-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    </button>
                                @endforeach
                            </div>
                        </template>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 dark:text-stone-300 mb-1">Cantidad (Pzas) <span class="text-red-500">*</span></label>
                            <input type="number" id="editQuantity" name="quantity" min="1" required class="w-full px-4 py-2 bg-white dark:bg-stone-800 border border-slate-200 dark:border-stone-700 rounded-lg text-sm text-slate-700 dark:text-stone-100 outline-none focus:border-orange-500">
                        </div>

                        {{-- Dropdown Personalizado: Prioridad --}}
                        <div 
                            class="relative" 
                            x-data="{ 
                                open: false, 
                                selectedPriority: 'medium',
                                selectedName: 'Media',
                                panelStyle: '',
                                options: {
                                    'low': 'Baja',
                                    'medium': 'Media',
                                    'high': 'Alta'
                                },
                                sync() {
                                    this.selectedPriority = this.$refs.hiddenInput.value || 'medium';
                                    this.selectedName = this.options[this.selectedPriority] || 'Media';
                                },
                                toggle() {
                                    this.open = !this.open;
                                    if (this.open) {
                                        this.$nextTick(() => {
                                            const rect = this.$refs.trigger.getBoundingClientRect();
                                            this.panelStyle = `top:${rect.bottom + 4}px; left:${rect.left}px; width:${rect.width}px;`;
                                        });
                                    }
                                }
                            }"
                            x-init="
                                sync();
                                $refs.hiddenInput.addEventListener('change', () => sync());
                                $refs.hiddenInput.addEventListener('input', () => sync());
                            "
                            @scroll.window.capture="open = false"
                            @closemodal.window="open = false"
                        >
                            <label class="block text-sm font-semibold text-slate-700 dark:text-stone-300 mb-1">Prioridad <span class="text-red-500">*</span></label>
                            
                            <input type="hidden" id="editPriority" name="priority" x-ref="hiddenInput" required>

                            <button 
                                type="button" 
                                x-ref="trigger"
                                @click="toggle()" 
                                @click.outside="open = false" 
                                class="w-full flex items-center justify-between px-4 py-2 bg-white dark:bg-stone-800 border border-slate-200 dark:border-stone-700 rounded-lg text-sm text-slate-700 dark:text-stone-100 outline-none focus:border-orange-500 cursor-pointer"
                            >
                                <span x-text="selectedName" class="truncate"></span>
                                <svg class="w-4 h-4 text-slate-400 dark:text-stone-500 transition-transform duration-200 shrink-0" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>

                            <template x-teleport="body">
                                <div 
                                    x-show="open" 
                                    x-cloak
                                    :style="panelStyle"
                                    x-transition:enter="transition ease-out duration-100"
                                    x-transition:enter-start="transform opacity-0 scale-95"
                                    x-transition:enter-end="transform opacity-100 scale-100"
                                    x-transition:leave="transition ease-in duration-75"
                                    x-transition:leave-start="transform opacity-100 scale-100"
                                    x-transition:leave-end="transform opacity-0 scale-95"
                                    class="fixed z-[9999] max-h-64 overflow-auto bg-white dark:bg-stone-800 border border-slate-200 dark:border-stone-700 rounded-lg shadow-2xl py-1 [scrollbar-width:none] [-ms-overflow-style:none] [&::-webkit-scrollbar]:hidden"
                                    style="display: none;"
                                >
                                    @foreach([
                                        'low' => 'Baja',
                                        'medium' => 'Media',
                                        'high' => 'Alta'
                                    ] as $val => $label)
                                        <button 
                                            type="button" 
                                            @click="selectedPriority = @js($val); $refs.hiddenInput.value = selectedPriority; sync(); open = false;"
                                            class="w-full text-left px-4 py-2 text-sm text-slate-700 dark:text-stone-300 hover:bg-orange-50 dark:hover:bg-stone-700 hover:text-orange-600 dark:hover:text-white transition-colors flex items-center justify-between cursor-pointer"
                                        >
                                            <span class="truncate">{{ $label }}</span>
                                            <svg x-show="selectedPriority === @js($val)" class="w-4 h-4 text-orange-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                        </button>
                                    @endforeach
                                </div>
                            </template>
                        </div>

                        {{-- Dropdown Personalizado: Operario --}}
                        <div 
                            class="relative" 
                            x-data="{ 
                                open: false, 
                                selectedId: '', 
                                selectedName: 'Sin asignar',
                                panelStyle: '',
                                options: {
                                    '': 'Sin asignar',
                                    @foreach($operarios as $operario)
                                        @js((string)$operario->id): @js($operario->name),
                                    @endforeach
                                },
                                sync() {
                                    this.selectedId = this.$refs.hiddenInput.value || '';
                                    this.selectedName = this.options[this.selectedId] || 'Sin asignar';
                                },
                                toggle() {
                                    this.open = !this.open;
                                    if (this.open) {
                                        this.$nextTick(() => {
                                            const rect = this.$refs.trigger.getBoundingClientRect();
                                            this.panelStyle = `top:${rect.bottom + 4}px; left:${rect.left}px; width:${rect.width}px;`;
                                        });
                                    }
                                }
                            }"
                            x-init="
                                sync();
                                $refs.hiddenInput.addEventListener('change', () => sync());
                                $refs.hiddenInput.addEventListener('input', () => sync());
                            "
                            @scroll.window.capture="open = false"
                            @closemodal.window="open = false"
                        >
                            <label class="block text-sm font-semibold text-slate-700 dark:text-stone-300 mb-1">Operario Asignado</label>
                            
                            <input type="hidden" id="editUserId" name="user_id" x-ref="hiddenInput">

                            <button 
                                type="button" 
                                x-ref="trigger"
                                @click="toggle()" 
                                @click.outside="open = false" 
                                class="w-full flex items-center justify-between px-4 py-2 bg-white dark:bg-stone-800 border border-slate-200 dark:border-stone-700 rounded-lg text-sm text-slate-700 dark:text-stone-100 outline-none focus:border-orange-500 cursor-pointer"
                            >
                                <span x-text="selectedName" class="truncate"></span>
                                <svg class="w-4 h-4 text-slate-400 dark:text-stone-500 transition-transform duration-200 shrink-0" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>

                            <template x-teleport="body">
                                <div 
                                    x-show="open" 
                                    x-cloak
                                    :style="panelStyle"
                                    x-transition:enter="transition ease-out duration-100"
                                    x-transition:enter-start="transform opacity-0 scale-95"
                                    x-transition:enter-end="transform opacity-100 scale-100"
                                    x-transition:leave="transition ease-in duration-75"
                                    x-transition:leave-start="transform opacity-100 scale-100"
                                    x-transition:leave-end="transform opacity-0 scale-95"
                                    class="fixed z-[9999] max-h-64 overflow-auto bg-white dark:bg-stone-800 border border-slate-200 dark:border-stone-700 rounded-lg shadow-2xl py-1 [scrollbar-width:none] [-ms-overflow-style:none] [&::-webkit-scrollbar]:hidden"
                                    style="display: none;"
                                >
                                    <button 
                                        type="button" 
                                        @click="selectedId = ''; $refs.hiddenInput.value = ''; sync(); open = false;"
                                        class="w-full text-left px-4 py-2 text-sm text-slate-700 dark:text-stone-300 hover:bg-orange-50 dark:hover:bg-stone-700 hover:text-orange-600 dark:hover:text-white transition-colors flex items-center justify-between cursor-pointer"
                                    >
                                        <span class="text-slate-400 dark:text-stone-500">Sin asignar</span>
                                        <svg x-show="selectedId === ''" class="w-4 h-4 text-orange-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    </button>

                                    @foreach($operarios as $operario)
                                        <button 
                                            type="button" 
                                            @click="selectedId = @js((string)$operario->id); $refs.hiddenInput.value = selectedId; sync(); open = false;"
                                            class="w-full text-left px-4 py-2 text-sm text-slate-700 dark:text-stone-300 hover:bg-orange-50 dark:hover:bg-stone-700 hover:text-orange-600 dark:hover:text-white transition-colors flex items-center justify-between cursor-pointer"
                                        >
                                            <span class="truncate">{{ $operario->name }}</span>
                                            <svg x-show="selectedId == @js((string)$operario->id)" class="w-4 h-4 text-orange-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                        </button>
                                    @endforeach
                                </div>
                            </template>
                        </div>

                        {{-- Dropdown Personalizado: Estado --}}
                        <div 
                            class="relative" 
                            x-data="{ 
                                open: false, 
                                selectedStatus: 'pending',
                                selectedName: 'Pendiente',
                                panelStyle: '',
                                options: {
                                    'pending': 'Pendiente',
                                    'in_progress': 'En Progreso',
                                    'completed': 'Completada',
                                    'cancelled': 'Cancelada'
                                },
                                sync() {
                                    this.selectedStatus = this.$refs.hiddenInput.value || 'pending';
                                    this.selectedName = this.options[this.selectedStatus] || 'Pendiente';
                                },
                                toggle() {
                                    this.open = !this.open;
                                    if (this.open) {
                                        this.$nextTick(() => {
                                            const rect = this.$refs.trigger.getBoundingClientRect();
                                            this.panelStyle = `top:${rect.bottom + 4}px; left:${rect.left}px; width:${rect.width}px;`;
                                        });
                                    }
                                }
                            }"
                            x-init="
                                sync();
                                $refs.hiddenInput.addEventListener('change', () => sync());
                                $refs.hiddenInput.addEventListener('input', () => sync());
                            "
                            @scroll.window.capture="open = false"
                            @closemodal.window="open = false"
                        >
                            <label class="block text-sm font-semibold text-slate-700 dark:text-stone-300 mb-1">Estado <span class="text-red-500">*</span></label>
                            
                            <input type="hidden" id="editStatus" name="status" x-ref="hiddenInput" required>

                            <button 
                                type="button" 
                                x-ref="trigger"
                                @click="toggle()" 
                                @click.outside="open = false" 
                                class="w-full flex items-center justify-between px-4 py-2 bg-white dark:bg-stone-800 border border-slate-200 dark:border-stone-700 rounded-lg text-sm text-slate-700 dark:text-stone-100 outline-none focus:border-orange-500 cursor-pointer"
                            >
                                <span x-text="selectedName" class="truncate"></span>
                                <svg class="w-4 h-4 text-slate-400 dark:text-stone-500 transition-transform duration-200 shrink-0" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>

                            <template x-teleport="body">
                                <div 
                                    x-show="open" 
                                    x-cloak
                                    :style="panelStyle"
                                    x-transition:enter="transition ease-out duration-100"
                                    x-transition:enter-start="transform opacity-0 scale-95"
                                    x-transition:enter-end="transform opacity-100 scale-100"
                                    x-transition:leave="transition ease-in duration-75"
                                    x-transition:leave-start="transform opacity-100 scale-100"
                                    x-transition:leave-end="transform opacity-0 scale-95"
                                    class="fixed z-[9999] max-h-64 overflow-auto bg-white dark:bg-stone-800 border border-slate-200 dark:border-stone-700 rounded-lg shadow-2xl py-1 [scrollbar-width:none] [-ms-overflow-style:none] [&::-webkit-scrollbar]:hidden"
                                    style="display: none;"
                                >
                                    @foreach([
                                        'pending' => 'Pendiente',
                                        'in_progress' => 'En Progreso',
                                        'completed' => 'Completada',
                                        'cancelled' => 'Cancelada'
                                    ] as $val => $label)
                                        <button 
                                            type="button" 
                                            @click="selectedStatus = @js($val); $refs.hiddenInput.value = selectedStatus; sync(); open = false;"
                                            class="w-full text-left px-4 py-2 text-sm text-slate-700 dark:text-stone-300 hover:bg-orange-50 dark:hover:bg-stone-700 hover:text-orange-600 dark:hover:text-white transition-colors flex items-center justify-between cursor-pointer"
                                        >
                                            <span class="truncate">{{ $label }}</span>
                                            <svg x-show="selectedStatus === @js($val)" class="w-4 h-4 text-orange-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                        </button>
                                    @endforeach
                                </div>
                            </template>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-700 dark:text-stone-300 mb-1">Estación</label>
                            <input type="text" id="editEstacion" name="estacion" class="w-full px-4 py-2 bg-white dark:bg-stone-800 border border-slate-200 dark:border-stone-700 rounded-lg text-sm text-slate-700 dark:text-stone-100 outline-none focus:border-orange-500">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-700 dark:text-stone-300 mb-1">Fecha Límite</label>
                            <input type="date" id="editEndDate" name="end_date" class="w-full px-4 py-2 bg-white dark:bg-stone-800 border border-slate-200 dark:border-stone-700 rounded-lg text-sm text-slate-700 dark:text-stone-100 outline-none focus:border-orange-500">
                        </div>

                        <!-- Campo oculto para fecha inicio -->
                        <input type="hidden" id="editStartDate" name="start_date">
                    </div>
                </div>

                <div class="px-6 py-4 bg-slate-50 dark:bg-stone-800/50 border-t border-slate-100 dark:border-stone-800 flex justify-end gap-3">
                    <button type="button" onclick="closeEditModal()" class="px-4 py-2 bg-white dark:bg-stone-800 border border-slate-200 dark:border-stone-700 text-slate-700 dark:text-stone-300 rounded-lg text-sm font-medium hover:bg-slate-50 dark:hover:bg-stone-700 transition-colors">Cancelar</button>
                    <button type="submit" class="px-5 py-2 bg-orange-600 text-white rounded-lg text-sm font-medium hover:bg-orange-700 shadow-sm shadow-orange-600/30 transition-colors">Actualizar Orden</button>
                </div>
            </form>
        </div>
    </div>
</div>