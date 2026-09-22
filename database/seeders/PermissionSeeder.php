<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    private const MODULES = [
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

    private const ACTIONS = [
        'view'   => 'Ver',
        'create' => 'Crear',
        'edit'   => 'Editar',
        'delete' => 'Borrar',
        'manage' => 'Gestionar',
    ];

    // Permisos especiales del módulo Operario: no son CRUD, quedan fuera
    // de la matriz y se gestionan aparte (ver AppServiceProvider).
    private const SPECIAL = [
        ['name' => 'Acceso Módulo Operario', 'slug' => 'access-operario'],
        ['name' => 'Ver Órdenes Asignadas',   'slug' => 'view-assigned-orders'],
        ['name' => 'Actualizar Progreso',     'slug' => 'update-progress'],
        ['name' => 'Reportar Incidencias',    'slug' => 'create-incidences'],
    ];

    public function run(): void
    {
        foreach (self::MODULES as $moduleSlug => $moduleName) {
            foreach (self::ACTIONS as $actionSlug => $actionName) {
                Permission::updateOrCreate(
                    ['slug' => "{$moduleSlug}.{$actionSlug}"],
                    [
                        'name'       => "{$actionName} {$moduleName}",
                        'module'     => $moduleSlug,
                        'action'     => $actionSlug,
                        'is_special' => false,
                    ]
                );
            }
        }

        foreach (self::SPECIAL as $permission) {
            Permission::updateOrCreate(
                ['slug' => $permission['slug']],
                [
                    'name'       => $permission['name'],
                    'module'     => null,
                    'action'     => null,
                    'is_special' => true,
                ]
            );
        }
    }
}