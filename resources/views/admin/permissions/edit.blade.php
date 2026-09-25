@extends('layouts.app')

@section('content')
@php
    $isSpecial = old('is_special',$permission->is_special ?? false);
    
    // Lista de módulos para el selector
    $modulesList = isset($modules) 
        ? (is_array($modules) ? array_keys($modules) :$modules->keys()->toArray()) 
        : ['usuarios', 'roles', 'permisos', 'empleados', 'asistencias', 'reportes', 'produccion', 'configuracion'];

    $currentModule = old('module',$permission->module);
    $isCustomInitial =$currentModule && !in_array($currentModule,$modulesList);
@endphp

<div class="p-6 max-w-3xl mx-auto">
    <!-- Encabezado -->
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-xl font-bold text-slate-900 dark:text-stone-100">
            Editar Permiso: <span class="text-orange-600 dark:text-orange-400">{{ $permission->name }}</span>
        </h1>
        <a href="{{ route('admin.permissions.index') }}" 
           class="px-3.5 py-2 bg-slate-100 dark:bg-stone-800 text-slate-600 dark:text-stone-300 text-xs font-semibold rounded-xl hover:bg-slate-200 dark:hover:bg-stone-700 transition-colors">
            ← Volver al listado
        </a>
    </div>

    <!-- Formulario Principal -->
    <form action="{{ route('admin.permissions.update', $permission) }}" method="POST" class="bg-white dark:bg-stone-900 border border-slate-200 dark:border-stone-800 rounded-2xl p-6 shadow-sm space-y-6">
        @csrf
        @method('PUT')

        <div x-data="{
            isSpecial: {{ json_encode((bool) $isSpecial) }},
            selectedModule: '{{ $isCustomInitial ? 'custom' : ($currentModule ?? '') }}',
            customModule: '{{ $isCustomInitial ? $currentModule : '' }}',
            action: '{{ old('action', $permission->action) }}',
            slug: '{{ old('slug', $permission->slug) }}',

            get activeModule() {
                return this.selectedModule === 'custom' ? this.customModule.trim().toLowerCase() : this.selectedModule;
            },

            get calculatedSlug() {
                if (this.isSpecial) {
                    return this.slug ? this.slug.trim().toLowerCase() : 'slug-personalizado';
                }
                if (this.activeModule && this.action) {
                    return `${this.activeModule}.${this.action}`;
                }
                return 'módulo.acción';
            }
        }" class="space-y-5">

            <!-- Selector de Tipo de Permiso -->
            <div>
                <label class="block text-xs font-bold text-slate-500 dark:text-stone-400 uppercase tracking-wider mb-2">
                    Tipo de Permiso
                </label>
                <div class="grid grid-cols-2 gap-3">
                    <label @click="isSpecial = false"
                           :class="!isSpecial 
                               ? 'border-orange-500 bg-orange-50/60 dark:bg-orange-950/30 text-orange-950 dark:text-orange-300 ring-2 ring-orange-500/20' 
                               : 'border-slate-200 dark:border-stone-800 bg-white dark:bg-stone-900 text-slate-600 dark:text-stone-400 hover:border-slate-300 dark:hover:border-stone-700'"
                           class="relative flex items-center gap-3 p-3 rounded-2xl border cursor-pointer transition-all">
                        <div :class="!isSpecial ? 'bg-orange-600 text-white' : 'bg-slate-100 dark:bg-stone-800 text-slate-400'"
                             class="p-2 rounded-xl transition-colors shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs font-bold leading-none">Estándar</p>
                            <p class="text-[11px] opacity-75 mt-1 leading-tight">Módulo + Acción</p>
                        </div>
                    </label>

                    <label @click="isSpecial = true"
                           :class="isSpecial 
                               ? 'border-orange-500 bg-orange-50/60 dark:bg-orange-950/30 text-orange-950 dark:text-orange-300 ring-2 ring-orange-500/20' 
                               : 'border-slate-200 dark:border-stone-800 bg-white dark:bg-stone-900 text-slate-600 dark:text-stone-400 hover:border-slate-300 dark:hover:border-stone-700'"
                           class="relative flex items-center gap-3 p-3 rounded-2xl border cursor-pointer transition-all">
                        <div :class="isSpecial ? 'bg-orange-600 text-white' : 'bg-slate-100 dark:bg-stone-800 text-slate-400'"
                             class="p-2 rounded-xl transition-colors shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 0121 9z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs font-bold leading-none">Especial</p>
                            <p class="text-[11px] opacity-75 mt-1 leading-tight">Slug libre</p>
                        </div>
                    </label>
                </div>

                <input type="hidden" name="is_special" :value="isSpecial ? '1' : '0'">
            </div>

            <!-- Nombre del Permiso -->
            <div>
                <label class="block text-xs font-bold text-slate-600 dark:text-stone-300 uppercase tracking-wide mb-1.5">
                    Nombre del Permiso <span class="text-red-500">*</span>
                </label>
                <input type="text" 
                       name="name" 
                       value="{{ old('name', $permission->name) }}" 
                       required
                       placeholder="ej. Ver Reportes de Ventas"
                       class="w-full bg-slate-50 dark:bg-stone-800/60 border border-slate-200 dark:border-stone-700/80 rounded-xl px-3.5 py-2.5 text-sm text-slate-800 dark:text-stone-100 placeholder-slate-400 dark:placeholder-stone-500 focus:bg-white dark:focus:bg-stone-800 focus:ring-2 focus:ring-orange-500/20 focus:border-orange-600 dark:focus:border-orange-500 outline-none transition-all">
                @error('name')
                    <p class="text-xs text-red-600 dark:text-red-400 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Campos Estándar: Módulo y Acción -->
            <div x-show="!isSpecial" 
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 -translate-y-1"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 class="space-y-4">

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- Selector de Módulo -->
                    <div>
                        <label class="block text-xs font-bold text-slate-600 dark:text-stone-300 uppercase tracking-wide mb-1.5">
                            Módulo del Sistema <span class="text-red-500">*</span>
                        </label>
                        <select x-model="selectedModule" 
                                class="w-full bg-slate-50 dark:bg-stone-800/60 border border-slate-200 dark:border-stone-700/80 rounded-xl px-3.5 py-2.5 text-sm text-slate-800 dark:text-stone-100 focus:bg-white dark:focus:bg-stone-800 focus:ring-2 focus:ring-orange-500/20 focus:border-orange-600 dark:focus:border-orange-500 outline-none transition-all">
                            <option value="">Selecciona un módulo</option>
                            @foreach($modulesList as$modSlug)
                                <option value="{{ $modSlug }}">
                                    {{ \Illuminate\Support\Str::headline($modSlug) }} ({{$modSlug }})
                                </option>
                            @endforeach
                            <option value="custom">+ Registrar un nuevo módulo...</option>
                        </select>
                    </div>

                    <!-- Selector de Acción -->
                    <div>
                        <label class="block text-xs font-bold text-slate-600 dark:text-stone-300 uppercase tracking-wide mb-1.5">
                            Acción <span class="text-red-500">*</span>
                        </label>
                        <select name="action" 
                                x-model="action"
                                class="w-full bg-slate-50 dark:bg-stone-800/60 border border-slate-200 dark:border-stone-700/80 rounded-xl px-3.5 py-2.5 text-sm text-slate-800 dark:text-stone-100 focus:bg-white dark:focus:bg-stone-800 focus:ring-2 focus:ring-orange-500/20 focus:border-orange-600 dark:focus:border-orange-500 outline-none transition-all">
                            <option value="">Selecciona una acción</option>
                            @if(isset($actions) && (is_array($actions) \vert{}\vert{} is_object($actions)))
                                @foreach($actions as $key =>$label)
                                    <option value="{{ $key }}" {{ old('action', $permission->action) ===$key ? 'selected' : '' }}>
                                        {{ $label }} ({{$key }})
                                    </option>
                                @endforeach
                            @endif
                        </select>
                        @error('action')
                            <p class="text-xs text-red-600 dark:text-red-400 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Campo para Módulo Personalizado -->
                <div x-show="selectedModule === 'custom'"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 -translate-y-1"
                     x-transition:enter-end="opacity-100 translate-y-0">
                    <label class="block text-xs font-bold text-slate-600 dark:text-stone-300 uppercase tracking-wide mb-1.5">
                        Nombre del Nuevo Módulo
                    </label>
                    <input type="text" 
                           x-model="customModule"
                           placeholder="ej. nominas"
                           class="w-full bg-slate-50 dark:bg-stone-800/60 border border-slate-200 dark:border-stone-700/80 rounded-xl px-3.5 py-2.5 text-sm font-mono text-slate-800 dark:text-stone-100 placeholder-slate-400 dark:placeholder-stone-500 focus:bg-white dark:focus:bg-stone-800 focus:ring-2 focus:ring-orange-500/20 focus:border-orange-600 dark:focus:border-orange-500 outline-none transition-all">
                </div>

                <input type="hidden" name="module" :value="activeModule">
                @error('module')
                    <p class="text-xs text-red-600 dark:text-red-400 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Campo Especial: Slug Personalizado -->
            <div x-show="isSpecial" 
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 -translate-y-1"
                 x-transition:enter-end="opacity-100 translate-y-0">
                
                <label class="block text-xs font-bold text-slate-600 dark:text-stone-300 uppercase tracking-wide mb-1.5">
                    Slug Personalizado <span class="text-red-500">*</span>
                </label>
                <input type="text" 
                       name="slug" 
                       x-model="slug"
                       placeholder="ej. access-special-dashboard"
                       class="w-full bg-slate-50 dark:bg-stone-800/60 border border-slate-200 dark:border-stone-700/80 rounded-xl px-3.5 py-2.5 text-sm font-mono text-slate-800 dark:text-stone-100 placeholder-slate-400 dark:placeholder-stone-500 focus:bg-white dark:focus:bg-stone-800 focus:ring-2 focus:ring-orange-500/20 focus:border-orange-600 dark:focus:border-orange-500 outline-none transition-all">
                @error('slug')
                    <p class="text-xs text-red-600 dark:text-red-400 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Previsualización del Slug Generado -->
            <div class="p-3.5 bg-slate-100/80 dark:bg-stone-800/50 rounded-2xl border border-slate-200/80 dark:border-stone-700/60 flex items-center justify-between gap-2">
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-slate-400 dark:text-stone-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span class="text-xs font-medium text-slate-600 dark:text-stone-300">Identificador Generado:</span>
                </div>
                <code class="font-mono text-xs px-2.5 py-1 rounded-lg bg-white dark:bg-stone-900 border border-slate-200 dark:border-stone-700 text-orange-600 dark:text-orange-400 font-bold tracking-wide shadow-sm"
                      x-text="calculatedSlug"></code>
            </div>

        </div>

        <!-- Botones de Acción -->
        <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100 dark:border-stone-800">
            <a href="{{ route('admin.permissions.index') }}" 
               class="px-4 py-2 bg-slate-100 dark:bg-stone-800 text-slate-700 dark:text-stone-300 text-xs font-semibold rounded-xl hover:bg-slate-200 dark:hover:bg-stone-700 transition-colors">
                Cancelar
            </a>
            <button type="submit" 
                    class="px-5 py-2 bg-orange-600 text-white text-xs font-semibold rounded-xl hover:bg-orange-700 shadow-md shadow-orange-600/20 transition-colors">
                Guardar Cambios
            </button>
        </div>
    </form>
</div>
@endsection