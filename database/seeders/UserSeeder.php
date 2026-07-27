<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@empresa.mx'],
            [
                'name' => 'Ana Martínez',
                'password' => Hash::make('password123'),
                'role' => 'admin',
                'email_verified_at' => now(),
            ]
        );

        User::updateOrCreate(
            ['email' => 'operario@empresa.mx'],
            [
                'name' => 'Roberto López',
                'password' => Hash::make('password123'),
                'role' => 'operario',
                'email_verified_at' => now(),
            ]
        );
    }
}