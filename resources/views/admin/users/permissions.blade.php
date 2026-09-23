@extends('layouts.app')

@section('content')
<div class="p-6 max-w-[1400px] mx-auto">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 dark:text-stone-100">Permisos de {{ $user->name }}</h1>
            <p class="text-sm text-slate-500 dark:text-stone-400 mt-1">{{ $user->email }}</p>
        </div>

        <div class="flex items-center gap-6">
            <!-- Botón Global (Seleccionar todo) -->
            <label class="relative inline-flex items-center gap-3 cursor-pointer group/global p-2 rounded-xl hover:bg-slate-50 dark:hover:bg-stone-800/50 transition-colors">
                <span class="text-xs font-bold uppercase tracking-widest text-slate-500 dark:text-stone-400 group-hover/global:text-orange-600 transition-colors">Seleccionar Todo</span>
                <input type="checkbox" id="selectAllGlobal" class="sr-only peer">
                <div class="w-6 h-6 rounded-lg border-2 border-slate-300 dark:border-stone-600 peer-checked:bg-orange-600 peer-checked:border-orange-600 transition-all flex items-center justify-center bg-white dark:bg-stone-900 shadow-sm">
                    <svg class="w-4 h-4 text-white scale-50 opacity-0 peer-checked:scale-100 peer-checked:opacity-100 transition-all duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
            </label>

            <a href="{{ route('admin.users.index') }}" class="text-sm font-medium text-slate-500 dark:text-stone-400 hover:text-slate-800 dark:hover:text-stone-200 transition-colors">
                ← Volver
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-6 px-4 py-3 bg-emerald-50 dark:bg-emerald-950/30 border border-emerald-200 dark:border-emerald-900/50 text-emerald-700 dark:text-emerald-400 rounded-xl text-sm font-medium">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('admin.users.permissions.update', $user) }}" method="POST">
        @csrf
        @method('PUT')

        <!-- Matriz de permisos -->
        @include('admin.users.components.permissions-matrix', [
            'modules' => $permissionModules,
            'special' => $specialPermissions,
            'selectedIds' => $selectedIds,
        ])

        <div class="flex justify-end gap-3 pt-8 mt-4">
            <a href="{{ route('admin.users.index') }}" class="px-5 py-2.5 bg-white dark:bg-stone-900 border border-slate-200 dark:border-stone-700 text-slate-700 dark:text-stone-300 text-sm font-medium rounded-xl hover:bg-slate-50 dark:hover:bg-stone-800 transition-colors">Cancelar</a>
            <button type="submit" class="px-6 py-2.5 bg-orange-600 text-white text-sm font-medium rounded-xl hover:bg-orange-700 shadow-lg shadow-orange-600/20 transition-all focus:ring-4 focus:ring-orange-500/20">Guardar Cambios</button>
        </div>
    </form>
</div>

<!-- Lógica para los selectores de permisos -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const globalCheckbox = document.getElementById('selectAllGlobal');
        const moduleCheckboxes = document.querySelectorAll('.select-module');
        const permissionCheckboxes = document.querySelectorAll('.permission-checkbox');

        if (!globalCheckbox) return;

        // 1. Click en "Seleccionar todos (Global)"
        globalCheckbox.addEventListener('change', function() {
            const isChecked = this.checked;
            permissionCheckboxes.forEach(cb => cb.checked = isChecked);
            moduleCheckboxes.forEach(cb => { cb.checked = isChecked; cb.indeterminate = false; });
            globalCheckbox.indeterminate = false;
        });

        // 2. Click en "Todo" (Todo el módulo)
        moduleCheckboxes.forEach(moduleCb => {
            moduleCb.addEventListener('change', function() {
                const targetClass = this.getAttribute('data-module');
                const targetCheckboxes = document.querySelectorAll('.' + targetClass);
                const isChecked = this.checked;

                targetCheckboxes.forEach(cb => cb.checked = isChecked);
                this.indeterminate = false;
                updateGlobalCheckboxState();
            });
        });

        // 3. Click individual
        permissionCheckboxes.forEach(cb => {
            cb.addEventListener('change', function() {
                const moduleClass = Array.from(this.classList).find(cls => cls.startsWith('module-'));
                updateModuleCheckboxState(moduleClass);
                updateGlobalCheckboxState();
            });
        });

        function updateModuleCheckboxState(moduleClass) {
            if (!moduleClass) return;
            const moduleCbs = document.querySelectorAll('.' + moduleClass);
            const selectModuleCb = document.querySelector('.select-module[data-module="' + moduleClass + '"]');

            if (selectModuleCb && moduleCbs.length > 0) {
                const checkedCount = Array.from(moduleCbs).filter(cb => cb.checked).length;
                selectModuleCb.checked = checkedCount === moduleCbs.length;
                selectModuleCb.indeterminate = checkedCount > 0 && checkedCount < moduleCbs.length;
            }
        }

        function updateGlobalCheckboxState() {
            if (permissionCheckboxes.length === 0) return;
            const checkedCount = Array.from(permissionCheckboxes).filter(cb => cb.checked).length;
            globalCheckbox.checked = checkedCount === permissionCheckboxes.length;
            globalCheckbox.indeterminate = checkedCount > 0 && checkedCount < permissionCheckboxes.length;
        }

        // 4. Inicialización
        moduleCheckboxes.forEach(moduleCb => {
            const targetClass = moduleCb.getAttribute('data-module');
            updateModuleCheckboxState(targetClass);
        });
        updateGlobalCheckboxState();
    });
</script>
@endsection