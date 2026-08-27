<div x-show="openAddStockModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div x-show="openAddStockModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 transition-opacity bg-black/70 backdrop-blur-sm" @click="openAddStockModal = false"></div>

        <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>

        <div x-show="openAddStockModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block w-full max-w-sm p-6 my-8 text-left align-middle transition-all transform bg-white dark:bg-stone-900 shadow-xl rounded-2xl border border-slate-200 dark:border-stone-800" x-data="{ providerSource: 'registrado', selectOpen: false, selectedProviderId: '', selectedProviderName: 'Selecciona un proveedor...' }">

            <div class="flex justify-between items-center mb-1">
                <h3 class="text-lg font-bold text-slate-800 dark:text-white">Agregar Stock</h3>
                <button @click="openAddStockModal = false" class="text-slate-400 hover:text-slate-600 dark:text-stone-500 dark:hover:text-white cursor-pointer">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            <p class="text-sm text-slate-500 dark:text-stone-400 mb-4" x-text="activeMaterial.name"></p>

            <div class="flex items-center justify-between p-3 mb-4 rounded-lg bg-slate-50 dark:bg-stone-950 border border-slate-200 dark:border-stone-800">
                <span class="text-xs font-semibold text-slate-500 dark:text-stone-400">Stock actual</span>
                <span class="text-sm font-bold text-slate-800 dark:text-white" x-text="activeMaterial.stock_actual + ' ' + activeMaterial.unit"></span>
            </div>

            <form :action="'/admin/almacen/material/' + activeMaterial.id + '/add-stock'" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-xs font-semibold text-slate-600 dark:text-stone-400">Cantidad que entró al almacén <span class="text-red-500">*</span></label>
                    <input type="number" step="0.01" min="0.01" name="quantity_added" required autofocus placeholder="Ej: 50" class="w-full mt-1 px-3 py-2 text-sm bg-slate-50 dark:bg-stone-950 border rounded-lg border-slate-300 dark:border-stone-700 text-slate-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-orange-500/50 focus:border-orange-500">
                    <p class="text-[10px] text-slate-400 dark:text-stone-500 mt-1">Esta cantidad se <strong>suma</strong> al stock actual, no lo reemplaza.</p>
                </div>

                {{-- Switch: proveedor registrado vs. entrada manual --}}
                <div>
                    <label class="block text-xs font-semibold text-slate-600 dark:text-stone-400 mb-1.5">¿Quién entregó el material? <span class="text-red-500">*</span></label>
                    <div class="flex items-center gap-1 p-1 rounded-lg bg-slate-100 dark:bg-stone-950 border border-slate-200 dark:border-stone-800 w-fit">
                        <button type="button" @click="providerSource = 'registrado'" :class="providerSource === 'registrado' ? 'bg-white dark:bg-stone-800 text-slate-800 dark:text-white shadow-sm' : 'text-slate-500 dark:text-stone-400'" class="px-3 py-1.5 rounded-md text-xs font-semibold transition-colors cursor-pointer">
                            Proveedor registrado
                        </button>
                        <button type="button" @click="providerSource = 'manual'" :class="providerSource === 'manual' ? 'bg-white dark:bg-stone-800 text-slate-800 dark:text-white shadow-sm' : 'text-slate-500 dark:text-stone-400'" class="px-3 py-1.5 rounded-md text-xs font-semibold transition-colors cursor-pointer">
                            Otro / persona
                        </button>
                    </div>
                    <input type="hidden" name="provider_source" :value="providerSource">
                </div>

                {{-- Opción A: proveedor del directorio (Custom Dropdown) --}}
                <div x-show="providerSource === 'registrado'" x-cloak class="relative">
                    <input type="hidden" name="proveedor_id" :value="selectedProviderId" :required="providerSource === 'registrado'">
                    
                    <button 
                        type="button" 
                        @click="selectOpen = !selectOpen" 
                        @click.outside="selectOpen = false" 
                        class="w-full flex items-center justify-between px-3 py-2 text-sm bg-slate-50 dark:bg-stone-950 border rounded-lg border-slate-300 dark:border-stone-700 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-orange-500/50 focus:border-orange-500 cursor-pointer"
                        :class="selectedProviderId ? 'text-slate-800 dark:text-white' : 'text-slate-400 dark:text-stone-500'"
                    >
                        <span x-text="selectedProviderName" class="truncate"></span>
                        <svg class="w-4 h-4 text-slate-400 dark:text-stone-500 transition-transform duration-200 shrink-0" :class="selectOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>

                    <div 
                        x-show="selectOpen" 
                        x-transition:enter="transition ease-out duration-100"
                        x-transition:enter-start="transform opacity-0 scale-95"
                        x-transition:enter-end="transform opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-75"
                        x-transition:leave-start="transform opacity-100 scale-100"
                        x-transition:leave-end="transform opacity-0 scale-95"
                        class="absolute left-0 right-0 z-50 mt-1 max-h-48 overflow-auto bg-white dark:bg-stone-950 border border-slate-200 dark:border-stone-800 rounded-lg shadow-xl py-1 [scrollbar-width:none] [-ms-overflow-style:none] [&::-webkit-scrollbar]:hidden"
                        style="display: none;"
                    >
                        @foreach($proveedores as $prov)
                            <button 
                                type="button" 
                                @click="selectedProviderId = '{{ $prov->id }}'; selectedProviderName = '{{ $prov->nombre }}'; selectOpen = false;"
                                class="w-full text-left px-3 py-2 text-sm text-slate-700 dark:text-stone-300 hover:bg-orange-50 dark:hover:bg-stone-800 hover:text-orange-600 dark:hover:text-white transition-colors flex items-center justify-between cursor-pointer"
                            >
                                <span>{{ $prov->nombre }}</span>
                                <svg x-show="selectedProviderId == '{{ $prov->id }}'" class="w-4 h-4 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </button>
                        @endforeach
                    </div>

                    @if($proveedores->isEmpty())
                        <p class="text-[10px] text-amber-600 dark:text-amber-400 mt-1">Aún no hay proveedores registrados en el directorio.</p>
                    @endif
                </div>

                {{-- Opción B: nombre libre --}}
                <div x-show="providerSource === 'manual'" x-cloak>
                    <input type="text" name="proveedor_manual" :required="providerSource === 'manual'" placeholder="Ej. Juan" class="w-full px-3 py-2 text-sm bg-slate-50 dark:bg-stone-950 border rounded-lg border-slate-300 dark:border-stone-700 text-slate-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-orange-500/50 focus:border-orange-500">
                </div>

                <div class="flex justify-end gap-3 mt-6">
                    <button type="button" @click="openAddStockModal = false" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 dark:bg-stone-800 dark:hover:bg-stone-700 dark:border dark:border-stone-700 text-slate-700 dark:text-stone-200 font-medium rounded-lg text-sm transition-colors cursor-pointer">Cancelar</button>
                    <button type="submit" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-lg text-sm transition-colors cursor-pointer">Agregar al Stock</button>
                </div>
            </form>
        </div>
    </div>
</div>