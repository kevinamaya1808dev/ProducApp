<div id="editTypeModal-{{ $type->id }}" class="fixed inset-0 z-50 bg-slate-900/50 dark:bg-slate-900/85 backdrop-blur-sm overflow-y-auto hidden">
    <div class="flex items-center justify-center min-h-screen px-4 py-8">
        <div class="relative w-full max-w-md bg-white dark:bg-stone-900 shadow-2xl rounded-2xl border border-slate-100 dark:border-stone-800">
            <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100 dark:border-stone-800 bg-slate-50/50 dark:bg-stone-800/50">
                <h3 class="text-lg font-bold text-slate-900 dark:text-stone-100">Editar Tipo</h3>
                <button type="button" onclick="closeModal('editTypeModal-{{ $type->id }}')" class="text-slate-400 dark:text-stone-400 hover:text-slate-600 dark:hover:text-stone-200 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            <form action="{{ route('component-types.update', $type->id) }}" method="POST">
                @csrf @method('PUT')
                <div class="px-6 py-6 space-y-5">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 dark:text-stone-300 mb-2">Nombre</label>
                        <input type="text" name="name" required value="{{ $type->name }}" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 dark:border-stone-700 bg-white dark:bg-stone-800 text-slate-700 dark:text-stone-100 placeholder-slate-400 dark:placeholder-stone-500 focus:border-orange-500 dark:focus:border-orange-500 focus:ring-1 focus:ring-orange-500 outline-none">
                    </div>
                    @include('admin.component-types.components.color-picker', ['selectedColor' => $type->color])
                </div>
                <div class="px-6 py-4 border-t border-slate-100 dark:border-stone-800 flex justify-end gap-3 bg-slate-50 dark:bg-stone-800/50 rounded-b-2xl">
                    <button type="button" onclick="closeModal('editTypeModal-{{ $type->id }}')" class="px-5 py-2.5 text-sm font-semibold text-slate-700 dark:text-stone-300 bg-white dark:bg-stone-800 border border-slate-300 dark:border-stone-700 rounded-lg hover:bg-slate-50 dark:hover:bg-stone-700 transition-colors">Cancelar</button>
                    <button type="submit" class="px-5 py-2.5 text-sm font-semibold text-white bg-orange-600 rounded-lg hover:bg-orange-700 shadow-sm transition-colors">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>