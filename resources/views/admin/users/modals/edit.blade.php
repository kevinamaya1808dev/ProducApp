<div id="editModal" class="fixed inset-0 z-50 overflow-y-auto" style="display:none;" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" onclick="closeEditModal()"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>

        <div class="inline-block align-bottom bg-white dark:bg-stone-900 rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl w-full border border-slate-200 dark:border-stone-800">
            <form id="editForm" action="#" method="POST">
                @csrf
                @method('PUT')
                
                <div class="px-6 py-4 border-b border-slate-100 dark:border-stone-800 flex justify-between items-center bg-slate-50 dark:bg-stone-800/50">
                    <h3 class="text-lg font-bold text-slate-900 dark:text-stone-100">Editar Operario</h3>
                    <button type="button" onclick="closeEditModal()" aria-label="Cerrar modal" class="text-slate-400 dark:text-stone-400 hover:text-slate-600 dark:hover:text-stone-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <div class="p-6 space-y-6 max-h-[70vh] overflow-y-auto">
                    <div>
                        <h4 class="text-xs font-bold text-slate-400 dark:text-stone-400 uppercase tracking-wider mb-3">Información del Colaborador</h4>

                        <!-- Fila 1: Nombre y Rol -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 dark:text-stone-300 mb-1">Nombre completo <span class="text-red-500">*</span></label>
                                <input type="text" id="editName" name="name" required class="w-full px-4 py-2 bg-white dark:bg-stone-800 border border-slate-200 dark:border-stone-700 rounded-lg text-sm text-slate-800 dark:text-stone-100 outline-none focus:border-orange-600 dark:focus:border-orange-500">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 dark:text-stone-300 mb-1">Rol <span class="text-red-500">*</span></label>
                                <select id="editRoleId" name="role_id" required class="w-full px-4 py-2 bg-white dark:bg-stone-800 border border-slate-200 dark:border-stone-700 rounded-lg text-sm text-slate-800 dark:text-stone-100 outline-none focus:border-orange-600 dark:focus:border-orange-500">
                                    <option value="" class="dark:bg-stone-800">Selecciona un rol</option>
                                    @foreach($roles as $role)
                                        <option value="{{ $role->id }}" class="dark:bg-stone-800">{{ $role->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Fila 2: Correo y Estado -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-4">
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 dark:text-stone-300 mb-1">Correo electrónico <span class="text-red-500">*</span></label>
                                <input type="email" id="editEmail" name="email" required class="w-full px-4 py-2 bg-white dark:bg-stone-800 border border-slate-200 dark:border-stone-700 rounded-lg text-sm text-slate-800 dark:text-stone-100 outline-none focus:border-orange-600 dark:focus:border-orange-500">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 dark:text-stone-300 mb-1">Estado de la cuenta <span class="text-red-500">*</span></label>
                                <select id="editActive" name="active" required class="w-full px-4 py-2 bg-white dark:bg-stone-800 border border-slate-200 dark:border-stone-700 rounded-lg text-sm text-slate-800 dark:text-stone-100 outline-none focus:border-orange-600 dark:focus:border-orange-500">
                                    <option value="1" class="dark:bg-stone-800">Activo</option>
                                    <option value="0" class="dark:bg-stone-800">Inactivo (Dado de baja)</option>
                                </select>
                            </div>
                        </div>

                        <!-- Fila 3: Puesto y Turno -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-4">
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 dark:text-stone-300 mb-1">Puesto</label>
                                <input type="text" id="editPuesto" name="puesto" placeholder="Ej. Operario Especialista" class="w-full px-4 py-2 bg-white dark:bg-stone-800 border border-slate-200 dark:border-stone-700 rounded-lg text-sm text-slate-800 dark:text-stone-100 placeholder-slate-400 dark:placeholder-stone-500 outline-none focus:border-orange-600 dark:focus:border-orange-500">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 dark:text-stone-300 mb-1">Turno</label>
                                <select id="editTurno" name="turno" class="w-full px-4 py-2 bg-white dark:bg-stone-800 border border-slate-200 dark:border-stone-700 rounded-lg text-sm text-slate-800 dark:text-stone-100 outline-none focus:border-orange-600 dark:focus:border-orange-500">
                                    <option value="" class="dark:bg-stone-800">Seleccionar turno</option>
                                    <option value="Matutino" class="dark:bg-stone-800">Matutino</option>
                                    <option value="Vespertino" class="dark:bg-stone-800">Vespertino</option>
                                    <option value="Nocturno" class="dark:bg-stone-800">Nocturno</option>
                                </select>
                            </div>
                        </div>

                        <!-- Fila 4: Estación y Meta Diaria -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-4">
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 dark:text-stone-300 mb-1">Estación de Trabajo</label>
                                <input type="text" id="editEstacion" name="estacion" placeholder="Ej. Estación 1" class="w-full px-4 py-2 bg-white dark:bg-stone-800 border border-slate-200 dark:border-stone-700 rounded-lg text-sm text-slate-800 dark:text-stone-100 placeholder-slate-400 dark:placeholder-stone-500 outline-none focus:border-orange-600 dark:focus:border-orange-500">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 dark:text-stone-300 mb-1">Meta Diaria (Unidades)</label>
                                <input type="number" id="editMetaDiaria" name="meta_diaria" min="0" placeholder="Ej. 150" class="w-full px-4 py-2 bg-white dark:bg-stone-800 border border-slate-200 dark:border-stone-700 rounded-lg text-sm text-slate-800 dark:text-stone-100 placeholder-slate-400 dark:placeholder-stone-500 outline-none focus:border-orange-600 dark:focus:border-orange-500">
                            </div>
                        </div>

                        <!-- Fila 5: Notas -->
                        <div class="mt-4">
                            <label class="block text-sm font-semibold text-slate-700 dark:text-stone-300 mb-1">Notas / Observaciones</label>
                            <textarea id="editNotas" name="notas" rows="2" placeholder="Observaciones o comentarios adicionales..." class="w-full px-4 py-2 bg-white dark:bg-stone-800 border border-slate-200 dark:border-stone-700 rounded-lg text-sm text-slate-800 dark:text-stone-100 placeholder-slate-400 dark:placeholder-stone-500 outline-none focus:border-orange-600 dark:focus:border-orange-500"></textarea>
                        </div>

                        <!-- Fila 6: Contraseña -->
                        <div class="mt-4 pt-4 border-t border-slate-100 dark:border-stone-800">
                            <label class="block text-sm font-semibold text-slate-700 dark:text-stone-300 mb-1">Nueva contraseña</label>
                            <input type="password" id="editPassword" name="password" placeholder="Dejar en blanco para no cambiarla" minlength="8" autocomplete="new-password" class="w-full px-4 py-2 bg-white dark:bg-stone-800 border border-slate-200 dark:border-stone-700 rounded-lg text-sm text-slate-800 dark:text-stone-100 placeholder-slate-400 dark:placeholder-stone-500 outline-none focus:border-orange-600 dark:focus:border-orange-500">
                            <p class="text-xs text-slate-400 dark:text-stone-500 mt-1">Déjalo vacío si no quieres cambiar la contraseña del operario.</p>
                        </div>
                    </div>

                    <!-- Habilidades de Producción -->
                    <div class="border-t border-slate-100 dark:border-stone-800 pt-5">
                        <h4 class="text-xs font-bold text-slate-400 dark:text-stone-400 uppercase tracking-wider mb-2">Habilidades de Operario</h4>
                        <p class="text-xs text-slate-500 dark:text-stone-400 mb-4">Selecciona las áreas o competencias que domina en la línea de producción.</p>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 bg-slate-50 dark:bg-stone-800/50 p-4 rounded-xl border border-slate-100 dark:border-stone-800">
                            @foreach(['Ensamblaje', 'Control de Calidad', 'Soldadura', 'Empaque y Etiquetado', 'Mantenimiento Preventivo', 'Operación de CNC'] as $skillOption)
                                <label class="flex items-center gap-3 p-2 bg-white dark:bg-stone-800 rounded-lg border border-slate-200 dark:border-stone-700 cursor-pointer">
                                    <input type="checkbox" name="skills[]" value="{{ $skillOption }}" class="edit-skill-checkbox w-4 h-4 text-orange-600 dark:bg-stone-700 rounded border-slate-300 dark:border-stone-600">
                                    <span class="text-sm font-semibold text-slate-800 dark:text-stone-200">{{ $skillOption }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- Permisos individuales -->
                    <div class="border-t border-slate-100 dark:border-stone-800 pt-5">
                        <h4 class="text-xs font-bold text-slate-400 dark:text-stone-400 uppercase tracking-wider mb-2">Permisos Individuales</h4>
                        <p class="text-xs text-slate-500 dark:text-stone-400 mb-4">
                            Estos permisos se otorgan directamente al usuario independientemente de su rol.
                        </p>

                        <input type="hidden" name="permissions" value="">

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 bg-slate-50 dark:bg-stone-800/50 p-4 rounded-xl border border-slate-100 dark:border-stone-800">
                            @forelse($permissions as $permission)
                                <label class="flex items-center gap-3 p-2 bg-white dark:bg-stone-800 rounded-lg border border-slate-200 dark:border-stone-700 cursor-pointer">
                                    <input type="checkbox" name="permissions[]" value="{{ $permission->id }}" class="permission-checkbox w-4 h-4 text-orange-600 dark:bg-stone-700 rounded border-slate-300 dark:border-stone-600">
                                    <span class="text-sm font-semibold text-slate-800 dark:text-stone-200">{{ $permission->name }}</span>
                                </label>
                            @empty
                                <p class="text-xs text-slate-400 dark:text-stone-500 col-span-full">No hay permisos registrados en el sistema todavía.</p>
                            @endforelse
                        </div>
                    </div>
                </div>

                <div class="px-6 py-4 bg-slate-50 dark:bg-stone-800/50 border-t border-slate-100 dark:border-stone-800 flex justify-end gap-3">
                    <button type="button" onclick="closeEditModal()" class="px-4 py-2 bg-white dark:bg-stone-800 border border-slate-200 dark:border-stone-700 text-slate-700 dark:text-stone-300 rounded-lg text-sm font-medium hover:bg-slate-50 dark:hover:bg-stone-700 transition-colors">Cancelar</button>
                    <button type="submit" class="px-5 py-2 bg-orange-600 text-white rounded-lg text-sm font-medium hover:bg-orange-700 transition-colors">Guardar Cambios</button>
                </div>
            </form>
        </div>
    </div>
</div>