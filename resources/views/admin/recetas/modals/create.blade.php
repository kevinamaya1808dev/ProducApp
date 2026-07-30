<div id="createModal" class="fixed inset-0 z-50 bg-slate-900/50 backdrop-blur-sm overflow-y-auto hidden">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
        <div class="relative inline-block w-full max-w-2xl text-left align-middle transition-all transform bg-white shadow-xl rounded-2xl">
            
            <!-- Header -->
            <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100">
                <h3 class="text-xl font-bold text-slate-900">Nuevo Operario</h3>
                <button type="button" onclick="closeCreateModal()" class="text-slate-400 hover:text-slate-600 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <!-- Body -->
            <form action="{{ route('users.store') }}" method="POST">
                @csrf
                <div class="px-6 py-6 max-h-[70vh] overflow-y-auto">
                    
                    <div class="mb-5">
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Nombre completo <span class="text-red-500">*</span></label>
                        <input type="text" name="name" required placeholder="Ej: Juan Pérez Martínez" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition-all text-slate-700">
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5">
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Rol</label>
                            <select name="role_id" required class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition-all text-slate-700 bg-white">
                                <option value="">Selecciona un rol</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role->id }}">{{ $role->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Turno</label>
                            <select name="turno" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition-all text-slate-700 bg-white">
                                <option value="Matutino">Matutino</option>
                                <option value="Vespertino">Vespertino</option>
                                <option value="Nocturno">Nocturno</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5">
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Estación</label>
                            <input type="text" name="planta" placeholder="Ej: Estación 4" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition-all text-slate-700">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Correo electrónico <span class="text-red-500">*</span></label>
                            <input type="email" name="email" required placeholder="operario@empresa.mx" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition-all text-slate-700">
                        </div>
                    </div>

                    <div class="mb-5">
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Contraseña Temporal <span class="text-red-500">*</span></label>
                        <input type="password" name="password" required class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition-all text-slate-700">
                    </div>

                    <!-- Habilidades (Mockup Visual) -->
                    <div class="mb-5">
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Habilidades</label>
                        <div class="flex flex-wrap gap-2">
                            <span class="px-3 py-1 bg-white border border-slate-200 text-slate-600 rounded-full text-xs cursor-pointer hover:border-blue-500 hover:text-blue-600">Costura</span>
                            <span class="px-3 py-1 bg-white border border-slate-200 text-slate-600 rounded-full text-xs cursor-pointer hover:border-blue-500 hover:text-blue-600">Acabados</span>
                            <span class="px-3 py-1 bg-white border border-slate-200 text-slate-600 rounded-full text-xs cursor-pointer hover:border-blue-500 hover:text-blue-600">Control Calidad</span>
                            <span class="px-3 py-1 bg-white border border-slate-200 text-slate-600 rounded-full text-xs cursor-pointer hover:border-blue-500 hover:text-blue-600">Corte</span>
                            <span class="px-3 py-1 bg-white border border-slate-200 text-slate-600 rounded-full text-xs cursor-pointer hover:border-blue-500 hover:text-blue-600">Maquinaria Industrial</span>
                        </div>
                    </div>

                    <div class="mb-2">
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Notas internas</label>
                        <textarea name="notas" rows="3" placeholder="Observaciones..." class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition-all text-slate-700 resize-none"></textarea>
                    </div>

                </div>

                <!-- Footer -->
                <div class="px-6 py-4 border-t border-slate-100 flex items-center justify-end gap-3 bg-slate-50 rounded-b-2xl">
                    <button type="button" onclick="closeCreateModal()" class="px-5 py-2.5 text-sm font-semibold text-slate-700 bg-white border border-slate-300 rounded-lg hover:bg-slate-50 transition-colors">
                        Cancelar
                    </button>
                    <button type="submit" class="px-5 py-2.5 text-sm font-semibold text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition-colors shadow-sm shadow-blue-600/30">
                        Registrar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>