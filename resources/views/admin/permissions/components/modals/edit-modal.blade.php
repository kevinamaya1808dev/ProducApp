{{-- Modal: Editar Permiso --}}
<div id="editPermissionModal" class="fixed inset-0 z-50 hidden items-center justify-center p-4">
    <div class="absolute inset-0 bg-slate-900/50 dark:bg-black/60 backdrop-blur-sm" onclick="closeEditModal()"></div>

    <div class="relative bg-white dark:bg-stone-900 border border-slate-200 dark:border-stone-800 rounded-2xl shadow-xl w-full max-w-2xl max-h-[90vh] overflow-y-auto p-6">

        <div class="flex items-center justify-between mb-6">
            <h2 class="text-lg font-bold text-slate-900 dark:text-stone-100">Editar Permiso</h2>
            <button type="button" onclick="closeEditModal()"
                    class="text-slate-400 hover:text-slate-600 dark:hover:text-stone-200 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <form id="editPermissionForm" method="POST" action="" class="space-y-6">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-xs font-bold text-slate-600 dark:text-stone-300 uppercase mb-1">
                    Nombre del Permiso <span class="text-red-500">*</span>
                </label>
                <input type="text" id="editName" name="name" required
                       class="w-full bg-white dark:bg-stone-800 border border-slate-200 dark:border-stone-700 rounded-xl px-3 py-2 text-sm text-slate-700 dark:text-stone-100 focus:ring-2 focus:ring-orange-500/20 focus:border-orange-600 dark:focus:border-orange-500 outline-none">
            </div>

            <div class="flex items-center gap-2 pb-5 border-b border-slate-100 dark:border-stone-800">
                <input type="checkbox" id="editIsSpecial" name="is_special" value="1"
                       onchange="document.getElementById('editModuleFields').classList.toggle('hidden', this.checked); document.getElementById('editSpecialFields').classList.toggle('hidden', !this.checked);"
                       class="w-4 h-4 rounded border-slate-300 dark:border-stone-600 text-orange-600 focus:ring-orange-500">
                <label for="editIsSpecial" class="text-sm font-semibold text-slate-700 dark:text-stone-300">Es un permiso especial (no sigue el esquema módulo + acción)</label>
            </div>

            {{-- ==================== Estándar: Módulo + Acción ==================== --}}
            <div id="editModuleFields" class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-600 dark:text-stone-300 uppercase mb-1">Módulo</label>
                    <input type="text" id="editModuleInput" name="module" placeholder="ej. reportes" list="editModuleOptionsList"
                           class="w-full bg-white dark:bg-stone-800 border border-slate-200 dark:border-stone-700 rounded-xl px-3 py-2 text-sm text-slate-700 dark:text-stone-100 focus:ring-2 focus:ring-orange-500/20 focus:border-orange-600 dark:focus:border-orange-500 outline-none">
                    <datalist id="editModuleOptionsList">
                        @foreach($moduleOptions as $moduleOption)
                            <option value="{{ $moduleOption }}"></option>
                        @endforeach
                    </datalist>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-600 dark:text-stone-300 uppercase mb-1">Acción</label>
                    <select id="editAction" name="action"
                            class="w-full bg-white dark:bg-stone-800 border border-slate-200 dark:border-stone-700 rounded-xl px-3 py-2 text-sm text-slate-700 dark:text-stone-100 focus:ring-2 focus:ring-orange-500/20 focus:border-orange-600 dark:focus:border-orange-500 outline-none">
                        <option value="">Selecciona una acción...</option>
                        @foreach($actions as $key => $label)
                            <option value="{{ $key }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- ==================== Especial: Slug libre ==================== --}}
            <div id="editSpecialFields" class="hidden">
                <label class="block text-xs font-bold text-slate-600 dark:text-stone-300 uppercase mb-1">Slug</label>
                <input type="text" id="editSlug" name="slug" placeholder="ej. access-operario"
                       class="w-full bg-white dark:bg-stone-800 border border-slate-200 dark:border-stone-700 rounded-xl px-3 py-2 text-sm font-mono text-slate-700 dark:text-stone-100 focus:ring-2 focus:ring-orange-500/20 focus:border-orange-600 dark:focus:border-orange-500 outline-none">
            </div>

            <div class="flex justify-between items-center gap-2 pt-2 border-t border-slate-100 dark:border-stone-800">
                <button type="button" id="editDeleteBtn"
                        class="px-4 py-2 bg-white dark:bg-stone-800 border border-red-200 dark:border-red-900/50 text-red-600 dark:text-red-400 text-sm font-medium rounded-xl hover:bg-red-50 dark:hover:bg-red-950/30 transition-colors">
                    Eliminar Permiso
                </button>
                <div class="flex gap-2">
                    <button type="button" onclick="closeEditModal()"
                            class="px-4 py-2 bg-white dark:bg-stone-800 border border-slate-200 dark:border-stone-700 text-slate-700 dark:text-stone-300 text-sm font-medium rounded-xl hover:bg-slate-50 dark:hover:bg-stone-700 transition-colors">Cancelar</button>
                    <button type="submit"
                            class="px-4 py-2 bg-orange-600 text-white text-sm font-medium rounded-xl hover:bg-orange-700 shadow-md shadow-orange-600/20 transition-colors">Guardar Cambios</button>
                </div>
            </div>
        </form>
    </div>
</div>