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

        Role::updateOrCreate(
            ['slug' => 'operario'],
            ['name' => 'Operario']
        );

        // El Administrador tiene todos los permisos de módulo/acción.
        // El Operario ya no se gestiona por permisos: su acceso se resuelve
        // por rol directamente (ver AppServiceProvider), así que no se le
        // sincroniza ningún permiso aquí.
        $adminRole->permissions()->sync(Permission::all());
    }
}