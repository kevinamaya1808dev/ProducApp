<div x-show="openDeleteModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div x-show="openDeleteModal" x-transition.opacity class="fixed inset-0 bg-black/70 backdrop-blur-sm" @click="openDeleteModal = false"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
        <div x-show="openDeleteModal" x-transition class="inline-block w-full max-w-md p-6 my-8 text-left align-middle transition-all transform bg-white dark:bg-stone-900 shadow-xl rounded-2xl border border-slate-200 dark:border-stone-800">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-bold text-slate-800 dark:text-white">Eliminar Proveedor</h3>
                <button @click="openDeleteModal = false" class="text-slate-400 hover:text-slate-600 dark:text-stone-500 dark:hover:text-white cursor-pointer"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
            </div>
            <p class="text-sm text-slate-600 dark:text-stone-300 mb-6">
                ¿Estás seguro de eliminar a <strong class="text-slate-800 dark:text-white" x-text="activeProveedor.nombre"></strong>? Esta acción no se puede deshacer.
            </p>
            <form :action="'/admin/proveedores/' + activeProveedor.id" method="POST" class="flex justify-end gap-3">
                @csrf
                @method('DELETE')
                <button type="button" @click="openDeleteModal = false" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 dark:bg-stone-800 dark:hover:bg-stone-700 dark:border dark:border-stone-700 text-slate-700 dark:text-stone-200 font-medium rounded-lg text-sm transition-colors cursor-pointer">Cancelar</button>
                <button type="submit" class="px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white font-bold rounded-lg text-sm transition-colors cursor-pointer">Sí, Eliminar</button>
            </form>
        </div>
    </div>
</div>