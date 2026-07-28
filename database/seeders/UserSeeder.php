<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Definir y Crear los Permisos del Sistema
        $permissionsList = [
            // Permisos de Administración y Modulos Generales
            ['name' => 'Acceso a Productos', 'slug' => 'access-products'],
            ['name' => 'Ver Órdenes', 'slug' => 'view-orders'],
            ['name' => 'Gestionar Órdenes', 'slug' => 'manage-orders'],
            ['name' => 'Ver Dashboard Admin', 'slug' => 'view-admin-dashboard'],
            ['name' => 'Gestionar Categorías', 'slug' => 'manage-categories'],
            ['name' => 'Gestionar Usuarios', 'slug' => 'manage-users'],
            ['name' => 'Gestionar Recetas', 'slug' => 'manage-recipes'],

            // Permisos del Módulo de Operarios
            ['name' => 'Acceso Módulo Operario', 'slug' => 'access-operario'],
            ['name' => 'Ver Órdenes Asignadas', 'slug' => 'view-assigned-orders'],
            ['name' => 'Actualizar Progreso', 'slug' => 'update-progress'],
            ['name' => 'Reportar Incidencias', 'slug' => 'create-incidences'],
        ];

        foreach ($permissionsList as $permissionData) {
            Permission::updateOrCreate(
                ['slug' => $permissionData['slug']],
                ['name' => $permissionData['name']]
            );
        }

        // 2. Crear los Roles Principales
        $adminRole = Role::updateOrCreate(
            ['slug' => 'admin'],
            ['name' => 'Administrador']
        );

        $operarioRole = Role::updateOrCreate(
            ['slug' => 'operario'],
            ['name' => 'Operario']
        );

        // 3. Asignación de Permisos a los Roles
        // El Administrador recibe TODOS los permisos creados
        $allPermissions = Permission::all();
        $adminRole->permissions()->sync($allPermissions);

        // El Operario recibe únicamente los permisos de su flujo de trabajo
        $operarioPermissions = Permission::whereIn('slug', [
            'access-operario',
            'view-assigned-orders',
            'update-progress',
            'create-incidences',
        ])->get();

        $operarioRole->permissions()->sync($operarioPermissions);

        // 4. Crear o Actualizar Usuarios y Asignar Roles (Pivote)
        
        // Usuario Administrador
        $adminUser = User::updateOrCreate(
            ['email' => 'admin@empresa.mx'],
            [   
                'name' => 'Ana Martínez',
                'password' => Hash::make('password123'),
                'email_verified_at' => now(),
            ]
        );
        // Asigna el rol mediante la tabla pivote role_user
        $adminUser->roles()->sync([$adminRole->id]);

        // Usuario Operario
        $operarioUser = User::updateOrCreate(
            ['email' => 'operario@empresa.mx'],
            [
                'name' => 'Roberto López',
                'password' => Hash::make('password123'),
                'email_verified_at' => now(),
            ]
        );
        // Asigna el rol mediante la tabla pivote role_user
        $operarioUser->roles()->sync([$operarioRole->id]);
    }
}