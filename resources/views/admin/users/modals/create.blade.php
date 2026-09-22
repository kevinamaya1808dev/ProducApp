<div id="createModal" class="fixed inset-0 z-50 overflow-y-auto" style="display:none;" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-slate-900/75 transition-opacity" onclick="closeCreateModal()"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>

        <div class="inline-block align-bottom bg-white dark:bg-stone-900 rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl w-full border border-slate-200/80 dark:border-stone-800">
            <form action="{{ route('admin.users.store') }}" method="POST">
                @csrf
                <div class="px-6 py-4 border-b border-slate-100 dark:border-stone-800 flex justify-between items-center bg-slate-50/75 dark:bg-stone-800/50">
                    <h3 class="text-lg font-bold text-slate-900 dark:text-stone-100">Crear Nuevo Operario</h3>
                    <button type="button" onclick="closeCreateModal()" aria-label="Cerrar modal" class="text-slate-400 dark:text-stone-400 hover:text-slate-600 dark:hover:text-stone-200 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <!-- Scrollbar estilizado: delgado, redondeado, con soporte para Firefox
                     (scrollbar-width/scrollbar-color) y estado hover en el thumb -->
                <div class="p-6 pr-4 space-y-6 max-h-[70vh] overflow-y-auto modal-scroll">
                    <div>
                        <h4 class="text-xs font-bold text-slate-400 dark:text-stone-400 uppercase tracking-wider mb-3">Información del Colaborador</h4>

                        <!-- Fila 1: Nombre y Rol -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 dark:text-stone-300 mb-1">Nombre completo <span class="text-red-500">*</span></label>
                                <input type="text" name="name" required value="{{ old('name') }}" class="w-full px-4 py-2 bg-white dark:bg-stone-800 border border-slate-200 dark:border-stone-700 rounded-lg text-sm text-slate-800 dark:text-stone-100 placeholder-slate-400 dark:placeholder-stone-500 outline-none focus:border-orange-600 dark:focus:border-orange-500 transition-colors" placeholder="Ej. Juan Pérez">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 dark:text-stone-300 mb-1">Rol <span class="text-red-500">*</span></label>
                                <div 
                                    class="relative" 
                                    x-data="{ 
                                        open: false, 
                                        selectedId: @js(old('role_id', '')), 
                                        selectedName: 'Selecciona un rol',
                                        options: {
                                            '': 'Selecciona un rol',
                                            @foreach($roles as $role)
                                                @js((string)$role->id): @js($role->name),
                                            @endforeach
                                        },
                                        init() {
                                            this.selectedName = this.options[this.selectedId] || 'Selecciona un rol';
                                        },
                                        select(id, name) {
                                            this.selectedId = id;
                                            this.selectedName = name;
                                            this.open = false;
                                        }
                                    }"
                                    x-init="init()"
                                >
                                    <input type="hidden" name="role_id" x-model="selectedId" required>

                                    <button 
                                        type="button" 
                                        @click="open = !open" 
                                        @click.outside="open = false" 
                                        class="w-full flex items-center justify-between px-4 py-2 bg-white dark:bg-stone-800 border border-slate-200 dark:border-stone-700 rounded-lg text-sm text-slate-800 dark:text-stone-100 outline-none focus:border-orange-600 dark:focus:border-orange-500 cursor-pointer transition-colors"
                                    >
                                        <span x-text="selectedName" class="truncate" :class="selectedId ? 'text-slate-800 dark:text-stone-100' : 'text-slate-400 dark:text-stone-400'"></span>
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
                                        class="absolute left-0 right-0 z-50 mt-1 max-h-48 overflow-auto bg-white dark:bg-stone-800 border border-slate-200 dark:border-stone-700 rounded-lg shadow-xl py-1 [&::-webkit-scrollbar]:w-1.5 [&::-webkit-scrollbar-thumb]:bg-slate-200 dark:[&::-webkit-scrollbar-thumb]:bg-stone-700 [&::-webkit-scrollbar-thumb]:rounded-full"
                                        style="display: none;"
                                    >
                                        <button 
                                            type="button" 
                                            @click="select('', 'Selecciona un rol')"
                                            class="w-full text-left px-4 py-2 text-sm text-slate-700 dark:text-stone-300 hover:bg-orange-50 dark:hover:bg-stone-700 hover:text-orange-600 dark:hover:text-white transition-colors flex items-center justify-between cursor-pointer"
                                        >
                                            <span class="text-slate-400 dark:text-stone-400">Selecciona un rol</span>
                                            <svg x-show="selectedId === ''" class="w-4 h-4 text-orange-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                        </button>

                                        @foreach($roles as $role)
                                            <button 
                                                type="button" 
                                                @click="select(@js((string)$role->id), @js($role->name))"
                                                class="w-full text-left px-4 py-2 text-sm text-slate-700 dark:text-stone-300 hover:bg-orange-50 dark:hover:bg-stone-700 hover:text-orange-600 dark:hover:text-white transition-colors flex items-center justify-between cursor-pointer"
                                            >
                                                <span class="truncate">{{ $role->name }}</span>
                                                <svg x-show="selectedId === @js((string)$role->id)" class="w-4 h-4 text-orange-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path>
                                                </svg>
                                            </button>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Fila 2: Email y Puesto -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-4">
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 dark:text-stone-300 mb-1">Correo electrónico <span class="text-red-500">*</span></label>
                                <input type="email" name="email" required value="{{ old('email') }}" class="w-full px-4 py-2 bg-white dark:bg-stone-800 border border-slate-200 dark:border-stone-700 rounded-lg text-sm text-slate-800 dark:text-stone-100 placeholder-slate-400 dark:placeholder-stone-500 outline-none focus:border-orange-600 dark:focus:border-orange-500 transition-colors" placeholder="correo@ejemplo.com">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 dark:text-stone-300 mb-1">Puesto</label>
                                <input type="text" name="puesto" placeholder="Ej. Operario de línea" class="w-full px-4 py-2 bg-white dark:bg-stone-800 border border-slate-200 dark:border-stone-700 rounded-lg text-sm text-slate-800 dark:text-stone-100 placeholder-slate-400 dark:placeholder-stone-500 outline-none focus:border-orange-600 dark:focus:border-orange-500 transition-colors">
                            </div>
                        </div>

                        <!-- Fila 3: Turno y Estación -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-4">
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 dark:text-stone-300 mb-1">Turno</label>
                                <input type="text" name="turno" placeholder="Ej. Matutino" class="w-full px-4 py-2 bg-white dark:bg-stone-800 border border-slate-200 dark:border-stone-700 rounded-lg text-sm text-slate-800 dark:text-stone-100 placeholder-slate-400 dark:placeholder-stone-500 outline-none focus:border-orange-600 dark:focus:border-orange-500 transition-colors">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 dark:text-stone-300 mb-1">Estación de Trabajo</label>
                                <input type="text" name="planta" placeholder="Ej. Estación 1" class="w-full px-4 py-2 bg-white dark:bg-stone-800 border border-slate-200 dark:border-stone-700 rounded-lg text-sm text-slate-800 dark:text-stone-100 placeholder-slate-400 dark:placeholder-stone-500 outline-none focus:border-orange-600 dark:focus:border-orange-500 transition-colors">
                            </div>
                        </div>

                        <!-- Fila 4: Meta Diaria -->
                        <div class="mt-4">
                            <label class="block text-sm font-semibold text-slate-700 dark:text-stone-300 mb-1">Meta Diaria (Unidades)</label>
                            <input type="number" name="meta_diaria" min="0" placeholder="Ej. 150" class="w-full px-4 py-2 bg-white dark:bg-stone-800 border border-slate-200 dark:border-stone-700 rounded-lg text-sm text-slate-800 dark:text-stone-100 placeholder-slate-400 dark:placeholder-stone-500 outline-none focus:border-orange-600 dark:focus:border-orange-500 transition-colors">
                        </div>

                        <!-- Fila 5: Contraseña -->
                        <div class="mt-4 pt-4 border-t border-slate-100 dark:border-stone-800">
                            <label class="block text-sm font-semibold text-slate-700 dark:text-stone-300 mb-1">Contraseña <span class="text-red-500">*</span></label>
                            <input type="password" name="password" required placeholder="Mínimo 8 caracteres" minlength="8" autocomplete="new-password" class="w-full px-4 py-2 bg-white dark:bg-stone-800 border border-slate-200 dark:border-stone-700 rounded-lg text-sm text-slate-800 dark:text-stone-100 placeholder-slate-400 dark:placeholder-stone-500 outline-none focus:border-orange-600 dark:focus:border-orange-500 transition-colors">
                        </div>
                    </div>

                </div>

                <div class="px-6 py-4 bg-slate-50/75 dark:bg-stone-800/50 border-t border-slate-100 dark:border-stone-800 flex justify-end gap-3">
                    <button type="button" onclick="closeCreateModal()" class="px-4 py-2 bg-white dark:bg-stone-800 border border-slate-200 dark:border-stone-700 text-slate-700 dark:text-stone-300 rounded-lg text-sm font-medium hover:bg-slate-50 dark:hover:bg-stone-700 transition-colors">Cancelar</button>
                    <button type="submit" class="px-5 py-2 bg-orange-600 text-white rounded-lg text-sm font-medium hover:bg-orange-700 transition-colors shadow-sm">Crear Operario</button>
                </div>
            </form>
        </div>
    </div>
</div>