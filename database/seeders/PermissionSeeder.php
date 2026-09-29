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

    public function run(): void
    {
        foreach (self::MODULES as $moduleSlug => $moduleName) {
            foreach (self::ACTIONS as $actionSlug => $actionName) {
                Permission::updateOrCreate(
                    ['slug' => "{$moduleSlug}.{$actionSlug}"],
                    [
                        'name'   => "{$actionName} {$moduleName}",
                        'module' => $moduleSlug,
                        'action' => $actionSlug,
                    ]
                );
            }
        }
    }
}