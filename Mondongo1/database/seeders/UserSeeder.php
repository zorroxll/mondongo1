<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'name' => 'Admin',
                'email' => 'admin@example.com',
                'password' => Hash::make('password'),
                'role_id' => 1, // ID del rol de administrador
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Mesero',
                'email' => 'mesero@example.com',
                'password' => Hash::make('password'),
                'role_id' => 2, // ID del rol de mesero
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Cocinero',
                'email' => 'cocinero@example.com',
                'password' => Hash::make('password'),
                'role_id' => 3, // ID del rol de cocinero
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
