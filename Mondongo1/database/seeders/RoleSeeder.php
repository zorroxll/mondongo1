<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    public function run()
    {
        Role::insert([
            ['nombre' => 'Administrador'],
            ['nombre' => 'Mesero'],
            ['nombre' => 'Cocinero'],
        ]);
    }
}

