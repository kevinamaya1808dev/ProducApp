@extends('layouts.app')

@section('content')
<div class="p-6 max-w-3xl mx-auto">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-xl font-bold text-slate-900 dark:text-stone-100">Nuevo Permiso</h1>
        <a href="{{ route('admin.permissions.index') }}" class="text-sm font-medium text-slate-500 dark:text-stone-400 hover:text-slate-700 dark:hover:text-stone-200 transition-colors">← Volver</a>
    </div>

    @php
        $oldActions = old('actions', $prefillAction ? [$prefillAction] : []);
        $oldNames = old('names', []);
    @endphp

    <form action="{{ route('admin.permissions.store') }}" method="POST" class="bg-white dark:bg-stone-900 rounded-2xl border border-slate-200 dark:border-stone-800 p-6 space-y-6">
        @csrf

        <div class="flex items-center gap-2 pb-5 border-b border-slate-100 dark:border-stone-800">
            <input type="checkbox" id="is_special" name="is_special" value="1" {{ old('is_special') ? 'checked' : '' }}
                   onchange="document.getElementById('moduleFields').classList.toggle('hidden', this.checked); document.getElementById('specialFields').classList.toggle('hidden', !this.checked);"
                   class="w-4 h-4 rounded border-slate-300 dark:border-stone-600 text-orange-600 focus:ring-orange-500">
            <label for="is_special" class="text-sm font-semibold text-slate-700 dark:text-stone-300">Es un permiso especial (no sigue el esquema módulo + acción)</label>
        </div>

        <!-- ==================== PERMISOS DE MÓDULO: crea varias acciones a la vez ==================== -->
        <div id="moduleFields" class="space-y-5 {{ old('is_special') ? 'hidden' : '' }}">
            <div>
                <label class="block text-xs font-bold text-slate-600 dark:text-stone-300 uppercase mb-1">Módulo</label>
                <input type="text" id="moduleInput" name="module" value="{{ old('module', $prefillModule) }}" placeholder="ej. reportes" list="moduleOptionsList"
                       oninput="updateNamePlaceholders()"
                       class="w-full bg-white dark:bg-stone-800 border border-slate-200 dark:border-stone-700 rounded-xl px-3 py-2 text-sm text-slate-700 dark:text-stone-100 focus:ring-2 focus:ring-orange-500/20 focus:border-orange-600 dark:focus:border-orange-500 outline-none">
                <datalist id="moduleOptionsList">
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
                        <button type="button" onclick="document.querySelectorAll('.action-checkbox').forEach(cb => cb.checked = true)" class="text-xs font-medium text-orange-600 dark:text-orange-400 hover:underline">Seleccionar todo</button>
                        <button type="button" onclick="document.querySelectorAll('.action-checkbox').forEach(cb => cb.checked = false)" class="text-xs font-medium text-slate-500 dark:text-stone-400 hover:underline">Ninguno</button>
                    </div>
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-5 gap-2">
                    @foreach($actions as $actionKey => $actionLabel)
                        <label class="flex items-center justify-center gap-2 px-3 py-2 border border-slate-200 dark:border-stone-700 rounded-xl cursor-pointer hover:border-orange-400 has-[:checked]:bg-orange-50 has-[:checked]:border-orange-500 dark:has-[:checked]:bg-stone-800 transition-colors">
                            <input type="checkbox" name="actions[]" value="{{ $actionKey }}" class="action-checkbox w-4 h-4 rounded border-slate-300 dark:border-stone-600 text-orange-600 focus:ring-orange-500" @checked(in_array($actionKey, $oldActions))>
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
                               class="action-name-input col-span-2 bg-white dark:bg-stone-800 border border-slate-200 dark:border-stone-700 rounded-xl px-3 py-2 text-sm text-slate-700 dark:text-stone-100 focus:ring-2 focus:ring-orange-500/20 focus:border-orange-600 dark:focus:border-orange-500 outline-none"
                               data-action-label="{{ $actionLabel }}">
                    </div>
                @endforeach
            </div>
        </div>

        <!-- ==================== PERMISO ESPECIAL: uno solo, nombre y slug libres ==================== -->
        <div id="specialFields" class="space-y-4 {{ old('is_special') ? '' : 'hidden' }}">
            <div>
                <label class="block text-xs font-bold text-slate-600 dark:text-stone-300 uppercase mb-1">Nombre</label>
                <input type="text" name="name" value="{{ old('name') }}"
                       class="w-full bg-white dark:bg-stone-800 border border-slate-200 dark:border-stone-700 rounded-xl px-3 py-2 text-sm text-slate-700 dark:text-stone-100 focus:ring-2 focus:ring-orange-500/20 focus:border-orange-600 dark:focus:border-orange-500 outline-none">
                @error('name')
                    <p class="text-xs text-red-600 dark:text-red-400 mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-600 dark:text-stone-300 uppercase mb-1">Slug</label>
                <input type="text" name="slug" value="{{ old('slug') }}" placeholder="ej. access-custom-module"
                       class="w-full bg-white dark:bg-stone-800 border border-slate-200 dark:border-stone-700 rounded-xl px-3 py-2 text-sm font-mono text-slate-700 dark:text-stone-100 focus:ring-2 focus:ring-orange-500/20 focus:border-orange-600 dark:focus:border-orange-500 outline-none">
                @error('slug')
                    <p class="text-xs text-red-600 dark:text-red-400 mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="flex justify-end gap-2 pt-2">
            <a href="{{ route('admin.permissions.index') }}" class="px-4 py-2 bg-white dark:bg-stone-800 border border-slate-200 dark:border-stone-700 text-slate-700 dark:text-stone-300 text-sm font-medium rounded-xl hover:bg-slate-50 dark:hover:bg-stone-700 transition-colors">Cancelar</a>
            <button type="submit" class="px-4 py-2 bg-orange-600 text-white text-sm font-medium rounded-xl hover:bg-orange-700 shadow-md shadow-orange-600/20 transition-colors">Crear Permiso(s)</button>
        </div>
    </form>
</div>

<script>
    function headline(str) {
        if (!str) return 'Módulo';
        return str
            .replace(/[_\-]+/g, ' ')
            .trim()
            .split(/\s+/)
            .filter(Boolean)
            .map(w => w.charAt(0).toUpperCase() + w.slice(1).toLowerCase())
            .join(' ');
    }

    function updateNamePlaceholders() {
        const label = headline(document.getElementById('moduleInput').value);
        document.querySelectorAll('.action-name-input').forEach(input => {
            input.placeholder = `${input.dataset.actionLabel} ${label}`;
        });
    }

    document.addEventListener('DOMContentLoaded', updateNamePlaceholders);
</script>
@endsection