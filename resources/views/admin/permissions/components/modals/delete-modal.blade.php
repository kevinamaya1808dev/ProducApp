{{-- Modal: Eliminar Permiso --}}
<div id="deletePermissionModal" class="fixed inset-0 z-50 hidden items-center justify-center p-4">
    <div class="absolute inset-0 bg-slate-900/50 dark:bg-black/60 backdrop-blur-sm" onclick="closeDeleteModal()"></div>

    <div class="relative bg-white dark:bg-stone-900 border border-slate-200 dark:border-stone-800 rounded-2xl shadow-xl w-full max-w-md p-6">
        <h2 class="text-lg font-bold text-slate-900 dark:text-stone-100 mb-2">Eliminar Permiso</h2>
        <p class="text-sm text-slate-600 dark:text-stone-400 mb-6">
            ¿Seguro que quieres eliminar el permiso
            <span id="deletePermissionName" class="font-semibold text-slate-800 dark:text-stone-200"></span>?
            Esta acción no se puede deshacer.
        </p>

        <form id="deletePermissionForm" method="POST" action="">
            @csrf
            @method('DELETE')
            <div class="flex justify-end gap-2">
                <button type="button" onclick="closeDeleteModal()"
                        class="px-4 py-2 bg-white dark:bg-stone-800 border border-slate-200 dark:border-stone-700 text-slate-700 dark:text-stone-300 text-sm font-medium rounded-xl hover:bg-slate-50 dark:hover:bg-stone-700 transition-colors">Cancelar</button>
                <button type="submit"
                        class="px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-xl hover:bg-red-700 shadow-md shadow-red-600/20 transition-colors">Eliminar</button>
            </div>
        </form>
    </div>
</div>