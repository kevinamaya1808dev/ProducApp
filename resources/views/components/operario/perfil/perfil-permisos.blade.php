@props(['permisos' => []])

@php
    // Nombres legibles por módulo (mismos nombres que la matriz de permisos de administración)
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

    $actionLabels = [
        'view'   => 'Ver',
        'create' => 'Crear',
        'edit'   => 'Editar',
        'delete' => 'Borrar',
        'manage' => 'Gestionar',
    ];

    // Agrupa los permisos por módulo. Reconoce dos formatos:
    //   - "Ver Dashboard" (acción + nombre del módulo)
    //   - "users.create"  (módulo.acción)
    // Lo que no encaje en ninguno se muestra tal cual, como antes.
    $accionesConocidas = array_map('mb_strtolower', array_values($actionLabels));

    $items = [];
    foreach ($permisos as $permiso) {
        $nombre = is_array($permiso) ? ($permiso['nombre'] ?? '') : $permiso;
        $slug   = is_array($permiso) ? ($permiso['modulo'] ?? null) : null;
        $accion = is_array($permiso) ? ($permiso['accion'] ?? null) : null;

        $moduloLabel = null;
        $accionTexto = null;

        if (! $slug && is_string($nombre) && str_contains($nombre, '.')) {
            [$slug, $accion] = explode('.', $nombre, 2) + [null, null];
        }

        if ($slug && preg_match('/^[A-Za-z0-9_-]+$/', $slug)) {
            $moduloLabel = $moduleLabels[$slug] ?? ucfirst(str_replace(['_', '-'], ' ', $slug));
            $accionTexto = $actionLabels[$accion] ?? ($accion ? ucfirst(str_replace(['_', '-', '.'], ' ', $accion)) : $nombre);
        } elseif (is_string($nombre)) {
            $partes = preg_split('/\s+/', trim($nombre), 2);
            if (count($partes) === 2 && in_array(mb_strtolower($partes[0]), $accionesConocidas, true)) {
                $accionTexto = $partes[0];
                $moduloLabel = $partes[1];
            }
        }

        if ($moduloLabel !== null) {
            $clave = 'm:' . mb_strtolower($moduloLabel);
            $items[$clave]['label'] = $moduloLabel;
            $items[$clave]['acciones'][$accionTexto] = $accionTexto;
        } else {
            $items[] = ['label' => $nombre];
        }
    }
@endphp

<div class="bg-white dark:bg-stone-900 rounded-2xl shadow-sm border border-amber-100 dark:border-stone-800 p-6">
    <h3 class="text-xs font-bold text-stone-400 dark:text-stone-500 uppercase tracking-wide mb-4">Permisos</h3>
    <div class="space-y-3">
        @forelse($items as $item)
            @if(isset($item['acciones']))
                {{-- Módulo con permisos específicos: se despliegan a la derecha al pasar el mouse --}}
                <div class="relative group/mod rounded-lg outline-none focus-visible:ring-2 focus-visible:ring-orange-500/40" tabindex="0">
                    <div class="flex items-center space-x-3">
                        <div class="w-6 h-6 rounded-full bg-emerald-50 dark:bg-emerald-950/50 border border-emerald-200 dark:border-emerald-900/50 flex items-center justify-center flex-shrink-0">
                            <svg class="w-3.5 h-3.5 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                        </div>
                        <h4 class="font-bold text-stone-800 dark:text-stone-100 text-sm">
                            {{ $item['label'] }}
                        </h4>
                        <svg class="ml-auto w-3.5 h-3.5 flex-shrink-0 rotate-90 lg:rotate-0 text-stone-300 dark:text-stone-600 group-hover/mod:text-orange-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"></path></svg>
                    </div>

                    {{-- Móvil/tablet: se abre debajo del módulo. Escritorio (lg): flota a la derecha. --}}
                    <div class="hidden group-hover/mod:block group-focus-visible/mod:block mt-2 ml-9 lg:ml-0 lg:mt-0 lg:block lg:absolute lg:left-full lg:top-0 lg:w-max lg:pl-9 lg:z-50 lg:invisible lg:opacity-0 lg:group-hover/mod:visible lg:group-hover/mod:opacity-100 lg:group-focus-visible/mod:visible lg:group-focus-visible/mod:opacity-100 lg:transition-opacity lg:duration-150 lg:motion-reduce:transition-none">
                        <div class="bg-white dark:bg-stone-900 border border-amber-100 dark:border-stone-700 rounded-xl shadow-sm lg:shadow-lg p-3 min-w-[11rem] max-w-[16rem]">
                            <p class="text-[10px] font-bold text-stone-400 dark:text-stone-500 uppercase tracking-wide mb-2">Permisos específicos</p>
                            <ul class="space-y-1.5">
                                @foreach($item['acciones'] as $accionTexto)
                                    <li class="flex items-center gap-2 text-xs font-semibold text-stone-700 dark:text-stone-300">
                                        <svg class="w-3 h-3 flex-shrink-0 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                        {{ $accionTexto }}
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @else
                {{-- Permiso sin módulo identificable: se muestra igual que antes --}}
                <div class="flex items-center space-x-3">
                    <div class="w-6 h-6 rounded-full bg-emerald-50 dark:bg-emerald-950/50 border border-emerald-200 dark:border-emerald-900/50 flex items-center justify-center flex-shrink-0">
                        <svg class="w-3.5 h-3.5 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                    </div>
                    <h4 class="font-bold text-stone-800 dark:text-stone-100 text-sm">
                        {{ $item['label'] }}
                    </h4>
                </div>
            @endif
        @empty
            <p class="text-sm text-stone-400 dark:text-stone-500">Aún no se han asignado permisos.</p>
        @endforelse
    </div>
</div>