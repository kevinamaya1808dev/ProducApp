@extends('layouts.app')

@section('content')
<style>[x-cloak] { display: none !important; }</style>

<div class="p-6 max-w-[1400px] mx-auto"
     x-data="{
        editMode: false,
        modalType: null, // 'edit' o 'delete'
        selectedModuleName: '',
        selectedModuleSlug: '',
        modulePermissions: [],
        
        // Estado del Modal de Confirmación
        confirmModalOpen: false,
        deleteTarget: null, // 'all' o el objeto de permiso individual
        isDeleting: false,

        openModal(type, name, slug, permissions) {
            this.modalType = type;
            this.selectedModuleName = name;
            this.selectedModuleSlug = slug;
            this.modulePermissions = permissions;
        },
        closeModal() {
            if (this.isDeleting) return;
            this.modalType = null;
            this.selectedModuleName = '';
            this.selectedModuleSlug = '';
            this.modulePermissions = [];
            this.closeConfirmModal();
        },
        promptDelete(target) {
            this.deleteTarget = target;
            this.confirmModalOpen = true;
        },
        closeConfirmModal() {
            if (this.isDeleting) return;
            this.confirmModalOpen = false;
            this.deleteTarget = null;
        },
        async executeDelete() {
            this.isDeleting = true;
            if (this.deleteTarget === 'all') {
                try {
                    const token = '{{ csrf_token() }}';
                    const promises = this.modulePermissions.map(perm => 
                        fetch(perm.destroy_url, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': token,
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({ _method: 'DELETE' })
                        })
                    );
                    await Promise.all(promises);
                    window.location.reload();
                } catch (error) {
                    this.isDeleting = false;
                }
            } else if (this.deleteTarget && this.deleteTarget.id) {
                const form = document.getElementById('delete-form-' + this.deleteTarget.id);
                if (form) {
                    form.submit();
                }
            }
        }
     }">

    <!-- Encabezado y Acciones Principales -->
    <div class="flex items-center justify-between gap-4 mb-6">
        <h1 class="text-xl font-bold text-slate-900 dark:text-stone-100">Gestión de Permisos</h1>
        
        <div class="flex items-center gap-2">
            <!-- Botón Modificar Módulos -->
            <button type="button"
                    @click="editMode = !editMode"
                    :class="editMode ? 'bg-orange-600 text-white border-orange-600' : 'bg-white dark:bg-stone-900 text-slate-700 dark:text-stone-300 border-slate-200 dark:border-stone-800 hover:bg-slate-50 dark:hover:bg-stone-800'"
                    class="px-3 py-2 text-xs font-semibold rounded-xl border shadow-sm transition-all flex items-center gap-1.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
                <span x-text="editMode ? 'Listo' : 'Modificar'">Modificar</span>
            </button>

            <!-- Botón Nuevo Permiso -->
            @can('users.manage')
                <a href="{{ route('admin.permissions.create') }}" class="px-4 py-2 bg-orange-600 text-white text-sm font-medium rounded-xl hover:bg-orange-700 shadow-md shadow-orange-600/20 transition-colors flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Nuevo Permiso
                </a>
            @endcan
        </div>
    </div>

    <!-- Tabla Matrix de Permisos por Módulo -->
    <div class="overflow-x-auto rounded-2xl border border-slate-200 dark:border-stone-800 bg-white dark:bg-stone-900 mb-8 shadow-sm">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-slate-50 dark:bg-stone-800/50 border-b border-slate-200 dark:border-stone-800">
                    <th class="text-left px-4 py-3 font-semibold text-slate-600 dark:text-stone-300 text-xs uppercase tracking-wide">Módulo</th>
                    @foreach($actions as $key => $label)
                        <th class="text-center px-3 py-3 font-semibold text-slate-600 dark:text-stone-300 text-xs uppercase tracking-wide">{{ $label }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-stone-800">
                @foreach($modules as $moduleSlug => $modulePermissions)
                    @php
                        $headlineModule = \Illuminate\Support\Str::headline($moduleSlug);
                        $formattedPermissions = $modulePermissions->map(function($p) {
                            return [
                                'id' => $p->id,
                                'name' => $p->name,
                                'action' => $p->action,
                                'slug' => $p->slug,
                                'edit_url' => route('admin.permissions.edit', $p),
                                'destroy_url' => route('admin.permissions.destroy', $p)
                            ];
                        })->values();
                    @endphp
                    <tr class="hover:bg-slate-50/60 dark:hover:bg-stone-800/40 transition-colors">
                        <td class="px-4 py-3 font-medium text-slate-700 dark:text-stone-200">
                            <div class="flex items-center justify-between gap-2">
                                <span>{{ $headlineModule }}</span>

                                <!-- Botones de Edición / Eliminación por Módulo (Visibles en Modo 'Modificar') -->
                                <div x-show="editMode" 
                                     x-transition 
                                     class="inline-flex items-center gap-1 bg-slate-100 dark:bg-stone-800 p-1 rounded-lg border border-slate-200 dark:border-stone-700">
                                    
                                    <!-- Editar Permisos del Módulo -->
                                    <button type="button"
                                            @click="openModal('edit', '{{ $headlineModule }}', '{{ $moduleSlug }}', {{ $formattedPermissions->toJson() }})"
                                            class="p-1 text-slate-500 hover:text-orange-600 dark:hover:text-orange-400 rounded transition-colors"
                                            title="Editar un permiso de {{ $headlineModule }}">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                                        </svg>
                                    </button>

                                    <!-- Eliminar Permisos del Módulo -->
                                    <button type="button"
                                            @click="openModal('delete', '{{ $headlineModule }}', '{{ $moduleSlug }}', {{ $formattedPermissions->toJson() }})"
                                            class="p-1 text-slate-500 hover:text-red-600 dark:hover:text-red-400 rounded transition-colors"
                                            title="Eliminar un permiso de {{ $headlineModule }}">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </td>
                        @foreach($actions as $actionKey => $actionLabel)
                            @php $perm = $modulePermissions->firstWhere('action', $actionKey); @endphp
                            <td class="text-center px-3 py-3">
                                @if($perm)
                                    <!-- Indicador estático naranja sin enlace ni función de click -->
                                    <span class="inline-flex items-center justify-center text-orange-600 dark:text-orange-400" title="{{ $perm->name }} ({{ $perm->slug }})">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    </span>
                                @else
                                    <span class="text-slate-300 dark:text-stone-700">—</span>
                                @endif
                            </td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Permisos Especiales (Operario) -->
    <h2 class="text-sm font-bold text-slate-500 dark:text-stone-400 uppercase tracking-wide mb-3">Permisos Especiales (Operario)</h2>
    <div class="rounded-2xl border border-slate-200 dark:border-stone-800 bg-white dark:bg-stone-900 divide-y divide-slate-100 dark:divide-stone-800 shadow-sm">
        @foreach($special as $permission)
            @php
                $specialPermData = [
                    'id' => $permission->id,
                    'name' => $permission->name,
                    'slug' => $permission->slug,
                    'destroy_url' => route('admin.permissions.destroy', $permission)
                ];
            @endphp
            <div class="flex items-center justify-between px-4 py-3">
                <div>
                    <p class="text-sm font-medium text-slate-700 dark:text-stone-200">{{ $permission->name }}</p>
                    <p class="text-xs font-mono text-slate-400 dark:text-stone-500">{{ $permission->slug }}</p>
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('admin.permissions.edit', $permission) }}" class="text-xs font-medium text-orange-600 dark:text-orange-400 hover:underline">Editar</a>
                    
                    <form id="delete-form-{{ $permission->id }}" action="{{ route('admin.permissions.destroy', $permission) }}" method="POST" class="hidden">
                        @csrf
                        @method('DELETE')
                    </form>
                    
                    <button type="button" 
                            @click="promptDelete({{ json_encode($specialPermData) }})" 
                            class="text-xs font-medium text-red-600 dark:text-red-400 hover:underline">
                        Eliminar
                    </button>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Modal Principal de Selección (Editar / Eliminar) -->
    <div x-show="modalType !== null"
         x-cloak
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">

        <div @click.away="closeModal()"
             class="bg-white dark:bg-stone-900 border border-slate-200 dark:border-stone-800 rounded-2xl shadow-xl w-full max-w-md p-6 space-y-4">
            
            <!-- Modal Header -->
            <div class="flex items-center justify-between pb-3 border-b border-slate-100 dark:border-stone-800">
                <h3 class="text-base font-bold text-slate-900 dark:text-stone-100">
                    <span x-text="modalType === 'edit' ? 'Editar Permiso:' : 'Eliminar Permiso:'"></span>
                    <span class="text-orange-600 dark:text-orange-400" x-text="selectedModuleName"></span>
                </h3>
                <button @click="closeModal()" type="button" class="text-slate-400 hover:text-slate-600 dark:hover:text-stone-200 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <!-- Modal Content (Modo Editar) -->
            <template x-if="modalType === 'edit'">
                <div class="space-y-3">
                    <p class="text-xs text-slate-500 dark:text-stone-400">
                        Selecciona cuál de los permisos del módulo <strong x-text="selectedModuleName"></strong> deseas modificar:
                    </p>
                    <div class="divide-y divide-slate-100 dark:divide-stone-800 max-h-60 overflow-y-auto pr-1">
                        <template x-for="perm in modulePermissions" :key="perm.id">
                            <div class="flex items-center justify-between py-2.5 px-3 hover:bg-slate-50 dark:hover:bg-stone-800/50 rounded-xl transition-colors">
                                <div>
                                    <p class="text-sm font-medium text-slate-700 dark:text-stone-200" x-text="perm.name"></p>
                                    <p class="text-xs font-mono text-slate-400 dark:text-stone-500" x-text="perm.slug"></p>
                                </div>
                                <a :href="perm.edit_url" 
                                   class="px-3 py-1 bg-orange-50 dark:bg-orange-950/40 text-orange-600 dark:text-orange-400 hover:bg-orange-600 hover:text-white dark:hover:bg-orange-500 dark:hover:text-white text-xs font-semibold rounded-lg transition-colors">
                                    Editar
                                </a>
                            </div>
                        </template>
                    </div>
                </div>
            </template>

            <!-- Modal Content (Modo Eliminar) -->
            <template x-if="modalType === 'delete'">
                <div class="space-y-3">
                    <!-- Opción Destacada: Eliminar Todos -->
                    <div class="flex items-center justify-between gap-2 p-3 bg-red-50 dark:bg-red-950/30 border border-red-200 dark:border-red-900/40 rounded-xl">
                        <div>
                            <p class="text-xs font-bold text-red-700 dark:text-red-400">¿Vaciar este módulo?</p>
                            <p class="text-[11px] text-red-600/80 dark:text-red-400/70">Elimina los <span x-text="modulePermissions.length"></span> permisos del módulo.</p>
                        </div>
                        <button type="button"
                                @click="promptDelete('all')"
                                class="px-3 py-1.5 bg-red-600 hover:bg-red-700 text-white text-xs font-semibold rounded-lg shadow-sm transition-colors flex items-center gap-1 shrink-0">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            Eliminar Todos
                        </button>
                    </div>

                    <p class="text-xs text-slate-500 dark:text-stone-400 pt-1">
                        O selecciona cuál permiso individual deseas eliminar:
                    </p>

                    <!-- Lista de Permisos para Eliminación Individual -->
                    <div class="divide-y divide-slate-100 dark:divide-stone-800 max-h-56 overflow-y-auto pr-1">
                        <template x-for="perm in modulePermissions" :key="perm.id">
                            <div class="flex items-center justify-between py-2.5 px-3 hover:bg-slate-50 dark:hover:bg-stone-800/50 rounded-xl transition-colors">
                                <div>
                                    <p class="text-sm font-medium text-slate-700 dark:text-stone-200" x-text="perm.name"></p>
                                    <p class="text-xs font-mono text-slate-400 dark:text-stone-500" x-text="perm.slug"></p>
                                </div>
                                
                                <form :id="'delete-form-' + perm.id" :action="perm.destroy_url" method="POST" class="hidden">
                                    @csrf
                                    @method('DELETE')
                                </form>

                                <button type="button" 
                                        @click="promptDelete(perm)"
                                        class="px-3 py-1 bg-red-50 dark:bg-red-950/40 text-red-600 dark:text-red-400 hover:bg-red-600 hover:text-white dark:hover:bg-red-500 dark:hover:text-white text-xs font-semibold rounded-lg transition-colors">
                                    Eliminar
                                </button>
                            </div>
                        </template>
                    </div>
                </div>
            </template>

            <!-- Modal Footer -->
            <div class="flex justify-end pt-3 border-t border-slate-100 dark:border-stone-800">
                <button @click="closeModal()" type="button" class="px-4 py-2 bg-slate-100 dark:bg-stone-800 text-slate-700 dark:text-stone-300 text-xs font-medium rounded-xl hover:bg-slate-200 dark:hover:bg-stone-700 transition-colors">
                    Cancelar
                </button>
            </div>

        </div>
    </div>

    <!-- Modal Secundario de Confirmación de Eliminación -->
    <div x-show="confirmModalOpen"
         x-cloak
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         class="fixed inset-0 z-[60] flex items-center justify-center p-4 bg-slate-950/70 backdrop-blur-md">

        <div @click.away="closeConfirmModal()"
             class="bg-white dark:bg-stone-900 border border-slate-200 dark:border-stone-800 rounded-2xl shadow-2xl w-full max-w-sm p-6 space-y-4 text-center">
            
            <!-- Icono de Advertencia -->
            <div class="mx-auto flex items-center justify-center w-12 h-12 rounded-full bg-red-100 dark:bg-red-950/50 text-red-600 dark:text-red-400">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
            </div>

            <!-- Textos Informativos del Modal de Confirmación -->
            <div class="space-y-1">
                <h3 class="text-base font-bold text-slate-900 dark:text-stone-100">
                    <template x-if="deleteTarget === 'all'">
                        <span>¿Eliminar TODOS los permisos?</span>
                    </template>
                    <template x-if="deleteTarget !== 'all'">
                        <span>¿Eliminar permiso?</span>
                    </template>
                </h3>
                <p class="text-xs text-slate-500 dark:text-stone-400 leading-relaxed">
                    <template x-if="deleteTarget === 'all'">
                        <span>Se eliminarán permanentemente los <strong class="text-slate-700 dark:text-stone-200" x-text="modulePermissions.length"></strong> permisos del módulo <strong class="text-orange-600 dark:text-orange-400" x-text="selectedModuleName"></strong> y se desvincularán de todos los usuarios.</span>
                    </template>
                    <template x-if="deleteTarget !== 'all' && deleteTarget">
                        <span>Se eliminará el permiso <strong class="text-slate-700 dark:text-stone-200" x-text="deleteTarget.name"></strong> (<span class="font-mono text-[11px]" x-text="deleteTarget.slug"></span>) de todos los usuarios asignados.</span>
                    </template>
                </p>
            </div>

            <!-- Botones de Confirmación -->
            <div class="flex items-center justify-center gap-2 pt-2">
                <button type="button"
                        @click="closeConfirmModal()"
                        :disabled="isDeleting"
                        class="w-full py-2 bg-slate-100 dark:bg-stone-800 text-slate-700 dark:text-stone-300 text-xs font-semibold rounded-xl hover:bg-slate-200 dark:hover:bg-stone-700 transition-colors">
                    Cancelar
                </button>
                <button type="button"
                        @click="executeDelete()"
                        :disabled="isDeleting"
                        class="w-full py-2 bg-red-600 text-white text-xs font-semibold rounded-xl hover:bg-red-700 shadow-md shadow-red-600/20 transition-colors flex items-center justify-center gap-1">
                    <span x-show="!isDeleting" x-text="deleteTarget === 'all' ? 'Sí, eliminar todos' : 'Sí, eliminar'"></span>
                    <span x-show="isDeleting" class="flex items-center gap-1">
                        <svg class="animate-spin h-3.5 w-3.5 text-white" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Eliminando...
                    </span>
                </button>
            </div>

        </div>
    </div>

</div>
@endsection