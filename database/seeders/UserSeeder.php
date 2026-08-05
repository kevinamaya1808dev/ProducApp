<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Buscar Roles creados previamente en RoleSeeder
        $adminRole = Role::where('slug', 'admin')->first();
        $operarioRole = Role::where('slug', 'operario')->first();

        // Crear Administrador
        $adminUser = User::updateOrCreate(
            ['email' => 'admin@empresa.mx'],
            [
                'name' => 'Ana Martínez',
                'password' => Hash::make('password123'),
                'email_verified_at' => now(),
            ]
        );
        if ($adminRole) {
            $adminUser->roles()->sync([$adminRole->id]);
        }

        // Crear Operario
        $operarioUser = User::updateOrCreate(
            ['email' => 'operario@empresa.mx'],
            [
                'name' => 'Roberto López',
                'password' => Hash::make('password123'),
                'email_verified_at' => now(),
            ]
        );
        if ($operarioRole) {
            $operarioUser->roles()->sync([$operarioRole->id]);
        }
    }
}