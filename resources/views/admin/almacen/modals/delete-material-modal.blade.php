<div x-show="openDeleteModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <!-- Fondo oscuro con efecto blur -->
        <div x-show="openDeleteModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 transition-opacity bg-slate-900/60 backdrop-blur-sm" @click="openDeleteModal = false"></div>

        <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>

        <!-- Contenedor del Modal -->
        <div x-show="openDeleteModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block w-full max-w-md p-6 my-8 text-left align-middle transition-all transform bg-white dark:bg-stone-800 shadow-xl rounded-2xl border border-slate-200 dark:border-stone-700">
            
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-bold text-slate-800 dark:text-stone-200">Eliminar Material</h3>
                <button @click="openDeleteModal = false" class="text-slate-400 hover:text-slate-600 dark:hover:text-stone-200 cursor-pointer">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <p class="text-sm text-slate-600 dark:text-stone-300 mb-6">
                ¿Estás seguro de que deseas eliminar el material <strong class="text-slate-800 dark:text-stone-100" x-text="activeMaterial.name"></strong>? Esta acción no se puede deshacer y podría afectar las recetas vinculadas.
            </p>

            <form :action="'/admin/almacen/material/' + activeMaterial.id" method="POST" class="flex justify-end gap-3">
                @csrf
                @method('DELETE')

                <button type="button" @click="openDeleteModal = false" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 dark:bg-stone-700 dark:hover:bg-stone-600 text-slate-700 dark:text-stone-300 font-medium rounded-lg text-sm transition-colors cursor-pointer">Cancelar</button>
                <button type="submit" class="px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white font-medium rounded-lg text-sm transition-colors cursor-pointer">Sí, Eliminar</button>
            </form>

        </div>
    </div>
</div>