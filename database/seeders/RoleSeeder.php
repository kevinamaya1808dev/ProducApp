<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Permission;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $adminRole = Role::updateOrCreate(
            ['slug' => 'admin'],
            ['name' => 'Administrador']
        );

        $operarioRole = Role::updateOrCreate(
            ['slug' => 'operario'],
            ['name' => 'Operario']
        );

        // Permisos completos del Administrador
        $adminPermissions = Permission::whereIn('slug', [
            'view-admin-dashboard',
            'view-categories', 'manage-categories',
            'view-products', 'access-products', 'manage-products',
            'view-recipes', 'manage-recipes',
            'view-orders', 'manage-orders',
            'view-users', 'manage-users',
        ])->get();

        $adminRole->permissions()->sync($adminPermissions);

        // Permisos base del Operario (Sin vistas administrativas por defecto)
        $operarioPermissions = Permission::whereIn('slug', [
            'access-operario',
            'view-assigned-orders',
            'update-progress',
            'create-incidences',
        ])->get();

        $operarioRole->permissions()->sync($operarioPermissions);
    }
}