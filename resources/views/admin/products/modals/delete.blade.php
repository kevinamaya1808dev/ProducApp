<div id="modal-delete" class="fixed inset-0 z-50 hidden bg-slate-900/50 backdrop-blur-sm overflow-y-auto items-center justify-center p-4">
    <div class="relative bg-white rounded-xl shadow-xl w-full max-w-sm transform transition-all text-center">
        <form id="delete_product_form" method="POST">
            @csrf
            @method('DELETE')

            <div class="px-6 py-8">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 mb-4">
                    <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                </div>
                <h3 class="text-lg font-bold text-slate-900 mb-2">¿Eliminar Producto?</h3>
                <p class="text-sm text-slate-500">
                    Esta acción no se puede deshacer. Los datos vinculados podrían verse afectados.
                </p>
            </div>

            <div class="px-6 py-4 bg-slate-50 rounded-b-xl border-t border-slate-100 flex justify-center gap-3 w-full">
                <button type="button" onclick="closeModal('modal-delete')" class="w-full px-4 py-2 text-sm font-semibold text-slate-600 bg-white border border-slate-200 rounded-lg hover:bg-slate-50">Cancelar</button>
                <button type="submit" class="w-full px-4 py-2 text-sm font-semibold text-white bg-red-600 rounded-lg hover:bg-red-700">Sí, Eliminar</button>
            </div>
        </form>
    </div>
</div>