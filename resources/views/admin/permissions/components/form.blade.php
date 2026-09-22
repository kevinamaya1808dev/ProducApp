@php
    $isSpecial = old('is_special', $permission?->is_special ?? false);
@endphp

<div class="flex items-center gap-2">
    <input type="checkbox" id="is_special" name="is_special" value="1" {{ $isSpecial ? 'checked' : '' }}
           onchange="document.getElementById('moduleActionFields').classList.toggle('hidden', this.checked); document.getElementById('slugField').classList.toggle('hidden', !this.checked);"
           class="w-4 h-4 rounded border-slate-300 dark:border-stone-600 text-orange-600 focus:ring-orange-500">
    <label for="is_special" class="text-sm font-semibold text-slate-700 dark:text-stone-300">Es un permiso especial (no sigue el esquema módulo.acción)</label>
</div>

<div>
    <label class="block text-xs font-bold text-slate-600 dark:text-stone-300 uppercase mb-1">Nombre</label>
    <input type="text" name="name" value="{{ old('name', $permission?->name) }}" required
           class="w-full bg-white dark:bg-stone-800 border border-slate-200 dark:border-stone-700 rounded-xl px-3 py-2 text-sm text-slate-700 dark:text-stone-100 focus:ring-2 focus:ring-orange-500/20 focus:border-orange-600 dark:focus:border-orange-500 outline-none">
</div>

<div id="moduleActionFields" class="grid grid-cols-2 gap-4 {{ $isSpecial ? 'hidden' : '' }}">
    <div>
        <label class="block text-xs font-bold text-slate-600 dark:text-stone-300 uppercase mb-1">Módulo</label>
        <input type="text" name="module" value="{{ old('module', $permission?->module) }}" placeholder="ej. reportes"
               class="w-full bg-white dark:bg-stone-800 border border-slate-200 dark:border-stone-700 rounded-xl px-3 py-2 text-sm text-slate-700 dark:text-stone-100 focus:ring-2 focus:ring-orange-500/20 focus:border-orange-600 dark:focus:border-orange-500 outline-none">
    </div>
    <div>
        <label class="block text-xs font-bold text-slate-600 dark:text-stone-300 uppercase mb-1">Acción</label>
        <select name="action" class="w-full bg-white dark:bg-stone-800 border border-slate-200 dark:border-stone-700 rounded-xl px-3 py-2 text-sm text-slate-700 dark:text-stone-100 focus:ring-2 focus:ring-orange-500/20 focus:border-orange-600 dark:focus:border-orange-500 outline-none">
            <option value="">Selecciona una acción</option>
            @foreach($actions as $key => $label)
                <option value="{{ $key }}" {{ old('action', $permission?->action) === $key ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
        </select>
    </div>
</div>

<div id="slugField" class="{{ $isSpecial ? '' : 'hidden' }}">
    <label class="block text-xs font-bold text-slate-600 dark:text-stone-300 uppercase mb-1">Slug</label>
    <input type="text" name="slug" value="{{ old('slug', $permission?->slug) }}" placeholder="ej. access-custom-module"
           class="w-full bg-white dark:bg-stone-800 border border-slate-200 dark:border-stone-700 rounded-xl px-3 py-2 text-sm font-mono text-slate-700 dark:text-stone-100 focus:ring-2 focus:ring-orange-500/20 focus:border-orange-600 dark:focus:border-orange-500 outline-none">
</div>

@error('slug')
    <p class="text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
@enderror