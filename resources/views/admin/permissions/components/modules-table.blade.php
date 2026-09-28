{{-- Tabla de permisos por módulo con modo "Modificar" (Editar/Eliminar por fila) --}}
<div class="overflow-x-auto rounded-2xl border border-slate-200 dark:border-stone-800 bg-white dark:bg-stone-900 mb-8">
    <table class="w-full text-sm">
        <thead>
            <tr class="bg-slate-50 dark:bg-stone-800/50 border-b border-slate-200 dark:border-stone-800">
                <th class="text-left px-4 py-3 font-semibold text-slate-600 dark:text-stone-300 text-xs uppercase tracking-wide w-1/4">Módulo</th>
                @foreach($actions as $key => $label)
                    <th class="text-center px-3 py-3 font-semibold text-slate-600 dark:text-stone-300 text-xs uppercase tracking-wide">{{ $label }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100 dark:divide-stone-800">
            @foreach($modules as $moduleSlug => $modulePermissions)
                <tr class="hover:bg-slate-50/60 dark:hover:bg-stone-800/40 transition-colors align-top">
                    <td class="px-4 py-3 font-medium text-slate-700 dark:text-stone-200">
                        <div class="flex items-center gap-2 flex-wrap">
                            <span>{{ \Illuminate\Support\Str::headline($moduleSlug) }}</span>

                            {{-- Botones Editar/Eliminar: visibles solo en modo Modificar --}}
                            <div class="manage-row-controls hidden items-center gap-1.5">
                                <button type="button" onclick="toggleRowSelector('{{ $moduleSlug }}', 'edit')"
                                        class="text-xs font-medium text-orange-600 dark:text-orange-400 hover:underline">Editar</button>
                                <span class="text-slate-300 dark:text-stone-700">·</span>
                                <button type="button" onclick="toggleRowSelector('{{ $moduleSlug }}', 'delete')"
                                        class="text-xs font-medium text-red-600 dark:text-red-400 hover:underline">Eliminar</button>
                            </div>
                        </div>

                        {{-- Selector dinámico: muestra solo las acciones que tiene este módulo --}}
                        <select id="rowSelector-{{ $moduleSlug }}" data-mode=""
                                class="row-selector hidden mt-2 w-full text-xs bg-white dark:bg-stone-800 border border-slate-200 dark:border-stone-700 rounded-lg px-2 py-1.5 text-slate-700 dark:text-stone-200 focus:ring-2 focus:ring-orange-500/20 focus:border-orange-600 dark:focus:border-orange-500 outline-none">
                            <option value="">Selecciona una acción...</option>
                            @foreach($modulePermissions as $perm)
                                @php
                                    $permData = $perm->only(['id', 'name', 'module', 'action', 'slug', 'is_special']);
                                @endphp
                                <option value="{{ $perm->id }}"
                                        data-name="{{ $perm->name }}"
                                        data-permission="@json($permData)">
                                    {{ $actions[$perm->action] ?? $perm->action }}
                                </option>
                            @endforeach
                        </select>
                    </td>

                    @foreach($actions as $actionKey => $actionLabel)
                        @php $perm = $modulePermissions->firstWhere('action', $actionKey); @endphp
                        <td class="text-center px-3 py-3">
                            @if($perm)
                                <svg class="w-4 h-4 mx-auto text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                            @else
                                <button type="button"
                                        onclick="openCreateModal('{{ $moduleSlug }}', '{{ $actionKey }}')"
                                        title="Crear permiso de {{ $actionLabel }} para {{ \Illuminate\Support\Str::headline($moduleSlug) }}"
                                        class="inline-flex items-center justify-center w-5 h-5 rounded text-slate-300 dark:text-stone-700 hover:text-orange-600 dark:hover:text-orange-400 hover:bg-orange-50 dark:hover:bg-stone-800 transition-colors">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                    </svg>
                                </button>
                            @endif
                        </td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>
</div>