@php
    // Nombres legibles por módulo (deben coincidir con los slugs de PermissionSeeder::MODULES)
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
@endphp

<div class="space-y-5">
    @forelse($modules as $moduleSlug => $modulePermissions)
        <div class="border border-slate-200 dark:border-stone-800 rounded-xl p-4">
            <h3 class="text-sm font-bold text-slate-800 dark:text-stone-100 mb-3">
                {{ $moduleLabels[$moduleSlug] ?? ucfirst($moduleSlug) }}
            </h3>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-x-4 gap-y-2">
                @foreach($modulePermissions as $permission)
                    <label class="flex items-center gap-2 text-sm text-slate-700 dark:text-stone-300 cursor-pointer">
                        <input
                            type="checkbox"
                            name="permissions[]"
                            value="{{ $permission->id }}"
                            @checked(in_array($permission->id, $selectedIds))
                            class="rounded border-slate-300 dark:border-stone-600 text-orange-600 focus:ring-orange-500"
                        >
                        {{ $permission->name }}
                    </label>
                @endforeach
            </div>
        </div>
    @empty
        <p class="text-sm text-slate-500 dark:text-stone-400">No hay permisos registrados todavía.</p>
    @endforelse

    @if($special->count())
        <div class="border-t border-slate-100 dark:border-stone-800 pt-5">
            <h3 class="text-xs font-bold text-slate-400 dark:text-stone-400 uppercase tracking-wider mb-3">
                Permisos especiales (Operario)
            </h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-4 gap-y-2">
                @foreach($special as $permission)
                    <label class="flex items-center gap-2 text-sm text-slate-700 dark:text-stone-300 cursor-pointer">
                        <input
                            type="checkbox"
                            name="permissions[]"
                            value="{{ $permission->id }}"
                            @checked(in_array($permission->id, $selectedIds))
                            class="rounded border-slate-300 dark:border-stone-600 text-orange-600 focus:ring-orange-500"
                        >
                        {{ $permission->name }}
                    </label>
                @endforeach
            </div>
        </div>
    @endif
</div>