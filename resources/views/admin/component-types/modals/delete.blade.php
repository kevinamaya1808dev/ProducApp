<div id="deleteTypeModal-{{ $type->id }}" class="fixed inset-0 z-50 bg-slate-900/50 dark:bg-slate-900/85 backdrop-blur-sm overflow-y-auto hidden">
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="relative w-full max-w-sm bg-white dark:bg-stone-900 shadow-2xl rounded-2xl border border-slate-100 dark:border-stone-800 p-6 text-center">
            <div class="mx-auto w-14 h-14 rounded-full bg-red-50 dark:bg-red-950/50 flex items-center justify-center mb-4">
                <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
            </div>
            <h3 class="text-lg font-bold text-slate-900 dark:text-stone-100 mb-1">Eliminar Tipo</h3>
            <p class="text-sm text-slate-500 dark:text-stone-400 mb-6">
                ¿Eliminar "{{ $type->name }}"?
                @if($type->components_count > 0)
                    <br><span class="text-red-500 dark:text-red-400 font-medium">Tiene {{ $type->components_count }} componente(s) asignados.</span>
                @endif
            </p>
            <form action="{{ route('component-types.destroy', $type->id) }}" method="POST">
                @csrf @method('DELETE')
                <div class="flex gap-3">
                    <button type="button" onclick="closeModal('deleteTypeModal-{{ $type->id }}')" class="flex-1 px-4 py-2.5 bg-slate-100 dark:bg-stone-800 hover:bg-slate-200 dark:hover:bg-stone-700 text-slate-700 dark:text-stone-300 text-sm font-semibold rounded-xl transition-colors">Cancelar</button>
                    <button type="submit" class="flex-1 px-4 py-2.5 bg-red-600 hover:bg-red-700 text-white text-sm font-semibold rounded-xl shadow-md shadow-red-600/20 transition-colors">Eliminar</button>
                </div>
            </form>
        </div>
    </div>
</div>