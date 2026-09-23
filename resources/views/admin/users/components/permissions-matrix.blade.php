@php
    // Nombres legibles por módulo (mismos nombres que PermissionSeeder::MODULES)
    $moduleLabels = [
        'dashboard'   => 'Dashboard',
        'categories'  => 'Categorías',
        'recipes'     => 'Recetas',
        'orders'      => 'Órdenes',
        'products'    => 'Productos',
        'almacen'     => 'Almacén',
        'proveedores' => 'Proveedores',
        'incidences'  => 'Incidencias',
        'users'       => 'Usuarios',
    ];

    $actionColumns = [
        'view'   => 'Ver',
        'create' => 'Crear',
        'edit'   => 'Editar',
        'delete' => 'Borrar',
        'manage' => 'Gestionar',
    ];
@endphp

<div class="overflow-x-auto rounded-2xl border border-slate-200 dark:border-stone-800 bg-white dark:bg-stone-900 shadow-sm">
    <table class="w-full text-left border-collapse">
        <thead class="border-b-2 border-slate-100 dark:border-stone-800/80 bg-slate-50/50 dark:bg-stone-900/50">
            <tr>
                <th class="px-6 py-5 text-xs font-bold text-slate-400 dark:text-stone-500 uppercase tracking-widest w-1/5">Módulo</th>
                @foreach($actionColumns as $label)
                    <th class="px-4 py-5 text-xs font-bold text-slate-400 dark:text-stone-500 uppercase tracking-widest text-center">{{ $label }}</th>
                @endforeach
                <th class="px-6 py-5 text-xs font-bold text-orange-500 dark:text-orange-600 uppercase tracking-widest text-center border-l border-slate-100 dark:border-stone-800/60">Todo</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100 dark:divide-stone-800/60">
            @forelse($modules as $moduleSlug => $modulePermissions)
                <tr class="hover:bg-slate-50/50 dark:hover:bg-stone-800/30 transition-colors group">
                    <!-- Nombre del Módulo -->
                    <td class="px-6 py-4 align-middle">
                        <span class="font-bold text-slate-800 dark:text-stone-100 text-sm tracking-wide">
                            {{ $moduleLabels[$moduleSlug] ?? ucfirst($moduleSlug) }}
                        </span>
                    </td>

                    @foreach($actionColumns as $actionKey => $actionLabel)
                        @php $perm = $modulePermissions->firstWhere('action', $actionKey); @endphp
                        <td class="px-4 py-4 text-center align-middle">
                            @if($perm)
                                <label class="relative inline-flex items-center justify-center cursor-pointer group/cb">
                                    <input type="checkbox" name="permissions[]" value="{{ $perm->id }}" @checked(in_array($perm->id, $selectedIds)) class="sr-only peer permission-checkbox module-{{ $moduleSlug }}">
                                    <div class="w-6 h-6 rounded border-2 border-slate-200 dark:border-stone-700 group-hover/cb:border-orange-400 peer-checked:bg-orange-500 peer-checked:border-orange-500 transition-all flex items-center justify-center bg-transparent">
                                        <svg class="w-4 h-4 text-white scale-50 opacity-0 peer-checked:scale-100 peer-checked:opacity-100 transition-all duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                                        </svg>
                                    </div>
                                </label>
                            @else
                                <span class="text-slate-300 dark:text-stone-700 font-light">—</span>
                            @endif
                        </td>
                    @endforeach

                    <!-- SELECCIONAR TODO EL MÓDULO -->
                    <td class="px-6 py-4 text-center align-middle border-l border-slate-100 dark:border-stone-800/60 bg-slate-50/30 dark:bg-stone-800/10">
                        <label class="relative inline-flex items-center justify-center cursor-pointer group/cb">
                            <input type="checkbox" class="sr-only peer select-module" data-module="module-{{ $moduleSlug }}">
                            <div class="w-6 h-6 rounded-lg border-2 border-slate-300 dark:border-stone-600 group-hover/cb:border-orange-500 peer-checked:bg-orange-600 peer-checked:border-orange-600 shadow-sm transition-all flex items-center justify-center bg-slate-50 dark:bg-stone-800">
                                <svg class="w-4 h-4 text-white scale-50 opacity-0 peer-checked:scale-100 peer-checked:opacity-100 transition-all duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                        </label>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="px-6 py-12 text-center text-slate-500 dark:text-stone-400">
                        No hay permisos registrados todavía.
                    </td>
                </tr>
            @endforelse

            <!-- Permisos Especiales -->
            @if($special->count())
                <tr class="bg-slate-50/50 dark:bg-stone-800/20 border-t border-slate-200 dark:border-stone-800">
                    <td class="px-6 py-5 align-middle">
                        <span class="font-bold text-slate-500 dark:text-stone-400 uppercase tracking-widest text-xs">
                            Especiales
                        </span>
                    </td>
                    <td colspan="5" class="px-4 py-5">
                        <div class="flex flex-wrap gap-x-8 gap-y-4">
                            @foreach($special as $permission)
                                <label class="relative inline-flex items-center gap-2.5 cursor-pointer group/item">
                                    <input type="checkbox" name="permissions[]" value="{{ $permission->id }}" @checked(in_array($permission->id, $selectedIds)) class="sr-only peer permission-checkbox module-special">
                                    <div class="w-5 h-5 rounded-md border-2 border-slate-200 dark:border-stone-700 peer-checked:bg-orange-500 peer-checked:border-orange-500 transition-all flex items-center justify-center bg-white dark:bg-stone-900">
                                        <svg class="w-3.5 h-3.5 text-white opacity-0 peer-checked:opacity-100 transition-opacity" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                                        </svg>
                                    </div>
                                    <span class="text-sm font-medium text-slate-600 dark:text-stone-300 group-hover/item:text-orange-600 dark:group-hover/item:text-orange-500 transition-colors">{{ $permission->name }}</span>
                                </label>
                            @endforeach
                        </div>
                    </td>
                    <td class="px-6 py-5 text-center align-middle border-l border-slate-100 dark:border-stone-800/60 bg-slate-50/30 dark:bg-stone-800/10">
                        <label class="relative inline-flex items-center justify-center cursor-pointer group/cb">
                            <input type="checkbox" class="sr-only peer select-module" data-module="module-special">
                            <div class="w-6 h-6 rounded-lg border-2 border-slate-300 dark:border-stone-600 group-hover/cb:border-orange-500 peer-checked:bg-orange-600 peer-checked:border-orange-600 shadow-sm transition-all flex items-center justify-center bg-slate-50 dark:bg-stone-800">
                                <svg class="w-4 h-4 text-white scale-50 opacity-0 peer-checked:scale-100 peer-checked:opacity-100 transition-all duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                        </label>
                    </td>
                </tr>
            @endif
        </tbody>
    </table>
</div>