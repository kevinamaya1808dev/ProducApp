<div id="editModal" class="fixed inset-0 z-50 overflow-y-auto" style="display:none;" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" onclick="closeEditModal()"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>

        <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl w-full border border-slate-200">
            <form id="editForm" action="#" method="POST">
                @csrf
                @method('PUT')
                <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center bg-slate-50">
                    <h3 class="text-lg font-bold text-slate-900">Editar Operario</h3>
                    <button type="button" onclick="closeEditModal()" class="text-slate-400 hover:text-slate-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <div class="p-6 space-y-6 max-h-[70vh] overflow-y-auto">
                    <div>
                        <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-3">Información del Colaborador</h4>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-1">Nombre completo <span class="text-red-500">*</span></label>
                                <input type="text" id="editName" name="name" required class="w-full px-4 py-2 border border-slate-200 rounded-lg text-sm outline-none focus:border-blue-500">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-1">Rol <span class="text-red-500">*</span></label>
                                <select id="editRoleId" name="role_id" required class="w-full px-4 py-2 border border-slate-200 rounded-lg text-sm outline-none focus:border-blue-500">
                                    <option value="">Selecciona un rol</option>
                                    @foreach($roles as $role)
                                        <option value="{{ $role->id }}">{{ $role->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="mt-4">
                            <label class="block text-sm font-semibold text-slate-700 mb-1">Correo electrónico <span class="text-red-500">*</span></label>
                            <input type="email" id="editEmail" name="email" required class="w-full px-4 py-2 border border-slate-200 rounded-lg text-sm outline-none focus:border-blue-500">
                        </div>

                        <div class="mt-4">
                            <label class="block text-sm font-semibold text-slate-700 mb-1">Nueva contraseña</label>
                            <input type="password" id="editPassword" name="password" placeholder="Dejar en blanco para no cambiarla" minlength="8" autocomplete="new-password" class="w-full px-4 py-2 border border-slate-200 rounded-lg text-sm outline-none focus:border-blue-500">
                            <p class="text-xs text-slate-400 mt-1">Déjalo vacío si no quieres cambiar la contraseña.</p>
                        </div>
                    </div>

                    <!-- Permisos individuales (independientes del rol) -->
                    <div class="border-t border-slate-100 pt-5">
                        <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Permisos Individuales</h4>
                        <p class="text-xs text-slate-500 mb-4">
                            Estos permisos se suman a los que el usuario ya tiene por su rol. Úsalos para dar acceso puntual a una función sin cambiarle el rol.
                        </p>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 bg-slate-50 p-4 rounded-xl border border-slate-100">
                            @forelse($permissions as $permission)
                                <label class="flex items-center gap-3 p-2 bg-white rounded-lg border border-slate-200 cursor-pointer">
                                    <input type="checkbox" name="permissions[]" value="{{ $permission->id }}" class="permission-checkbox w-4 h-4 text-blue-600 rounded border-slate-300">
                                    <span class="text-sm font-semibold text-slate-800">{{ $permission->name }}</span>
                                </label>
                            @empty
                                <p class="text-xs text-slate-400 col-span-full">No hay permisos registrados en el sistema todavía.</p>
                            @endforelse
                        </div>
                    </div>
                </div>

                <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex justify-end gap-3">
                    <button type="button" onclick="closeEditModal()" class="px-4 py-2 bg-white border border-slate-200 text-slate-700 rounded-lg text-sm font-medium">Cancelar</button>
                    <button type="submit" class="px-5 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700">Guardar Cambios</button>
                </div>
            </form>
        </div>
    </div>
</div>