{{-- Modal: Crear Permiso(s) --}}
<div id="createPermissionModal" class="fixed inset-0 z-50 hidden items-center justify-center p-4">
    <div class="absolute inset-0 bg-slate-900/50 dark:bg-black/60 backdrop-blur-sm" onclick="closeCreateModal()"></div>

    <div class="relative bg-white dark:bg-stone-900 border border-slate-200 dark:border-stone-800 rounded-2xl shadow-xl w-full max-w-3xl max-h-[90vh] overflow-y-auto p-6">

        <div class="flex items-center justify-between mb-6">
            <h2 class="text-lg font-bold text-slate-900 dark:text-stone-100">Nuevo Permiso</h2>
            <button type="button" onclick="closeCreateModal()"
                    class="text-slate-400 hover:text-slate-600 dark:hover:text-stone-200 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        @php
            $oldActions = old('actions', []);
            $oldNames = old('names', []);
        @endphp

        <form id="createPermissionForm" action="{{ route('admin.permissions.store') }}" method="POST"
              onsubmit="return validateCreatePermissionForm()" class="space-y-6">
            @csrf

            <div class="flex items-center gap-2 pb-5 border-b border-slate-100 dark:border-stone-800">
                <input type="checkbox" id="createIsSpecial" name="is_special" value="1" {{ old('is_special') ? 'checked' : '' }}
                       onchange="document.getElementById('createModuleFields').classList.toggle('hidden', this.checked); document.getElementById('createSpecialFields').classList.toggle('hidden', !this.checked);"
                       class="w-4 h-4 rounded border-slate-300 dark:border-stone-600 text-orange-600 focus:ring-orange-500">
                <label for="createIsSpecial" class="text-sm font-semibold text-slate-700 dark:text-stone-300">Es un permiso especial (no sigue el esquema módulo + acción)</label>
            </div>

            {{-- ==================== PERMISOS DE MÓDULO: crea varias acciones a la vez ==================== --}}
            <div id="createModuleFields" class="space-y-5 {{ old('is_special') ? 'hidden' : '' }}">
                <div>
                    <label class="block text-xs font-bold text-slate-600 dark:text-stone-300 uppercase mb-1">Módulo</label>
                    <input type="text" id="createModuleInput" name="module" value="{{ old('module') }}" placeholder="ej. reportes" list="createModuleOptionsList"
                           oninput="updateCreateNamePlaceholders()"
                           class="w-full bg-white dark:bg-stone-800 border border-slate-200 dark:border-stone-700 rounded-xl px-3 py-2 text-sm text-slate-700 dark:text-stone-100 focus:ring-2 focus:ring-orange-500/20 focus:border-orange-600 dark:focus:border-orange-500 outline-none">
                    <datalist id="createModuleOptionsList">
                        @foreach($moduleOptions as $moduleOption)
                            <option value="{{ $moduleOption }}"></option>
                        @endforeach
                    </datalist>
                    <p class="text-xs text-slate-400 dark:text-stone-500 mt-1">
                        Si escribes un módulo que ya existe, se le completan las acciones que le falten. Se guarda siempre en minúsculas y sin espacios raros, sin importar cómo lo escribas aquí.
                    </p>
                    @error('module')
                        <p class="text-xs text-red-600 dark:text-red-400 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <div class="flex items-center justify-between mb-2">
                        <label class="block text-xs font-bold text-slate-600 dark:text-stone-300 uppercase">Acciones a crear</label>
                        <div class="flex gap-3">
                            <button type="button" onclick="document.querySelectorAll('.create-action-checkbox').forEach(cb => cb.checked = true)" class="text-xs font-medium text-orange-600 dark:text-orange-400 hover:underline">Seleccionar todo</button>
                            <button type="button" onclick="document.querySelectorAll('.create-action-checkbox').forEach(cb => cb.checked = false)" class="text-xs font-medium text-slate-500 dark:text-stone-400 hover:underline">Ninguno</button>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 sm:grid-cols-5 gap-2">
                        @foreach($actions as $actionKey => $actionLabel)
                            <label class="flex items-center justify-center gap-2 px-3 py-2 border border-slate-200 dark:border-stone-700 rounded-xl cursor-pointer hover:border-orange-400 has-[:checked]:bg-orange-50 has-[:checked]:border-orange-500 dark:has-[:checked]:bg-stone-800 transition-colors">
                                <input type="checkbox" name="actions[]" value="{{ $actionKey }}" class="create-action-checkbox w-4 h-4 rounded border-slate-300 dark:border-stone-600 text-orange-600 focus:ring-orange-500" @checked(in_array($actionKey, $oldActions))>
                                <span class="text-sm font-medium text-slate-700 dark:text-stone-300">{{ $actionLabel }}</span>
                            </label>
                        @endforeach
                    </div>
                    @error('actions')
                        <p class="text-xs text-red-600 dark:text-red-400 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="space-y-3 pt-3 border-t border-slate-100 dark:border-stone-800">
                    <p class="text-xs text-slate-500 dark:text-stone-400">Nombre a mostrar por acción (opcional). Si lo dejas vacío, se genera solo a partir del módulo.</p>

                    @foreach($actions as $actionKey => $actionLabel)
                        <div class="grid grid-cols-3 gap-3 items-center">
                            <label class="text-sm text-slate-600 dark:text-stone-400 col-span-1">{{ $actionLabel }}</label>
                            <input type="text" name="names[{{ $actionKey }}]" value="{{ $oldNames[$actionKey] ?? '' }}"
                                   class="create-action-name-input col-span-2 bg-white dark:bg-stone-800 border border-slate-200 dark:border-stone-700 rounded-xl px-3 py-2 text-sm text-slate-700 dark:text-stone-100 focus:ring-2 focus:ring-orange-500/20 focus:border-orange-600 dark:focus:border-orange-500 outline-none"
                                   data-action-label="{{ $actionLabel }}">
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- ==================== PERMISO ESPECIAL: uno solo, nombre y slug libres ==================== --}}
            <div id="createSpecialFields" class="space-y-4 {{ old('is_special') ? '' : 'hidden' }}">
                <div>
                    <label class="block text-xs font-bold text-slate-600 dark:text-stone-300 uppercase mb-1">Nombre</label>
                    <input type="text" id="createSpecialName" name="name" value="{{ old('name') }}"
                           class="w-full bg-white dark:bg-stone-800 border border-slate-200 dark:border-stone-700 rounded-xl px-3 py-2 text-sm text-slate-700 dark:text-stone-100 focus:ring-2 focus:ring-orange-500/20 focus:border-orange-600 dark:focus:border-orange-500 outline-none">
                    @error('name')
                        <p class="text-xs text-red-600 dark:text-red-400 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-600 dark:text-stone-300 uppercase mb-1">Slug</label>
                    <input type="text" id="createSpecialSlug" name="slug" value="{{ old('slug') }}" placeholder="ej. access-custom-module"
                           class="w-full bg-white dark:bg-stone-800 border border-slate-200 dark:border-stone-700 rounded-xl px-3 py-2 text-sm font-mono text-slate-700 dark:text-stone-100 focus:ring-2 focus:ring-orange-500/20 focus:border-orange-600 dark:focus:border-orange-500 outline-none">
                    @error('slug')
                        <p class="text-xs text-red-600 dark:text-red-400 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="flex justify-end gap-2 pt-2 border-t border-slate-100 dark:border-stone-800">
                <button type="button" onclick="closeCreateModal()"
                        class="px-4 py-2 bg-white dark:bg-stone-800 border border-slate-200 dark:border-stone-700 text-slate-700 dark:text-stone-300 text-sm font-medium rounded-xl hover:bg-slate-50 dark:hover:bg-stone-700 transition-colors">Cancelar</button>
                <button type="submit"
                        class="px-4 py-2 bg-orange-600 text-white text-sm font-medium rounded-xl hover:bg-orange-700 shadow-md shadow-orange-600/20 transition-colors">Crear Permiso(s)</button>
            </div>
        </form>
    </div>
</div>