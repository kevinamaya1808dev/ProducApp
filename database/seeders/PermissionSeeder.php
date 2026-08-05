<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permission;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            // Dashboard
            ['name' => 'Ver Dashboard Admin', 'slug' => 'view-admin-dashboard'],

            // Categorías
            ['name' => 'Ver Categorías', 'slug' => 'view-categories'],
            ['name' => 'Gestionar Categorías', 'slug' => 'manage-categories'],

            // Productos
            ['name' => 'Ver Productos', 'slug' => 'view-products'],
            ['name' => 'Gestionar Productos', 'slug' => 'manage-products'],

            // Recetas
            ['name' => 'Ver Recetas', 'slug' => 'view-recipes'],
            ['name' => 'Gestionar Recetas', 'slug' => 'manage-recipes'],

            // Órdenes
            ['name' => 'Ver Órdenes', 'slug' => 'view-orders'],
            ['name' => 'Gestionar Órdenes', 'slug' => 'manage-orders'],

            // Usuarios
            ['name' => 'Ver Usuarios', 'slug' => 'view-users'],
            ['name' => 'Gestionar Usuarios', 'slug' => 'manage-users'],

            // Módulo Operario
            ['name' => 'Acceso Módulo Operario', 'slug' => 'access-operario'],
            ['name' => 'Ver Órdenes Asignadas', 'slug' => 'view-assigned-orders'],
            ['name' => 'Actualizar Progreso', 'slug' => 'update-progress'],
            ['name' => 'Reportar Incidencias', 'slug' => 'create-incidences'],
        ];

        foreach ($permissions as $permission) {
            Permission::updateOrCreate(
                ['slug' => $permission['slug']],
                ['name' => $permission['name']]
            );
        }
    }
}