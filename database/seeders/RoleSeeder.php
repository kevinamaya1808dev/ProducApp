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

        // Permisos completos del Administrador: todos los permisos "normales"
        // (no especiales) generados por PermissionSeeder para cada módulo/acción.
        // Los especiales del Operario quedan fuera a propósito.
        $adminPermissions = Permission::where('is_special', false)->get();

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