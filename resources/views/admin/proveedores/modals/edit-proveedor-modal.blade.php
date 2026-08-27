<div x-show="openEditModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div x-show="openEditModal" x-transition.opacity class="fixed inset-0 bg-black/70 backdrop-blur-sm" @click="openEditModal = false"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
        <div x-show="openEditModal" x-transition class="inline-block w-full max-w-lg p-6 my-8 text-left align-middle transition-all transform bg-white dark:bg-stone-900 shadow-xl rounded-2xl border border-slate-200 dark:border-stone-800">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-bold text-slate-800 dark:text-white">Editar Proveedor</h3>
                <button @click="openEditModal = false" class="text-slate-400 hover:text-slate-600 dark:text-stone-500 dark:hover:text-white cursor-pointer"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
            </div>
            <form :action="'/admin/proveedores/' + activeProveedor.id" method="POST" class="space-y-4">
                @csrf
                @method('PUT')
                <div>
                    <label class="block text-xs font-semibold text-slate-600 dark:text-stone-400">Nombre / Empresa <span class="text-red-500">*</span></label>
                    <input type="text" name="nombre" x-model="activeProveedor.nombre" required class="w-full mt-1 px-3 py-2 text-sm bg-slate-50 dark:bg-stone-950 border rounded-lg border-slate-300 dark:border-stone-700 text-slate-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-orange-500/50 focus:border-orange-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 dark:text-stone-400">Nombre de Contacto</label>
                    <input type="text" name="contacto_nombre" x-model="activeProveedor.contacto_nombre" class="w-full mt-1 px-3 py-2 text-sm bg-slate-50 dark:bg-stone-950 border rounded-lg border-slate-300 dark:border-stone-700 text-slate-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-orange-500/50 focus:border-orange-500">
                </div>
                <div class="grid grid-cols-2 gap-2">
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 dark:text-stone-400">Teléfono</label>
                        <input type="text" name="telefono" x-model="activeProveedor.telefono" class="w-full mt-1 px-3 py-2 text-sm bg-slate-50 dark:bg-stone-950 border rounded-lg border-slate-300 dark:border-stone-700 text-slate-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-orange-500/50 focus:border-orange-500">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 dark:text-stone-400">Correo Electrónico</label>
                        <input type="email" name="email" x-model="activeProveedor.email" class="w-full mt-1 px-3 py-2 text-sm bg-slate-50 dark:bg-stone-950 border rounded-lg border-slate-300 dark:border-stone-700 text-slate-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-orange-500/50 focus:border-orange-500">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 dark:text-stone-400">Dirección</label>
                    <textarea name="direccion" x-model="activeProveedor.direccion" rows="2" class="w-full mt-1 px-3 py-2 text-sm bg-slate-50 dark:bg-stone-950 border rounded-lg border-slate-300 dark:border-stone-700 text-slate-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-orange-500/50 focus:border-orange-500"></textarea>
                </div>
                <div class="flex justify-end gap-3 mt-6">
                    <button type="button" @click="openEditModal = false" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 dark:bg-stone-800 dark:hover:bg-stone-700 dark:border dark:border-stone-700 text-slate-700 dark:text-stone-200 font-medium rounded-lg text-sm transition-colors cursor-pointer">Cancelar</button>
                    <button type="submit" class="px-4 py-2 bg-orange-600 hover:bg-orange-700 text-white font-bold rounded-lg text-sm transition-colors cursor-pointer">Actualizar Proveedor</button>
                </div>
            </form>
        </div>
    </div>
</div>