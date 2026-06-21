<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    public function run(): void
    {
        Department::create([
            'name' => 'Suporte Técnico',
            'description' => 'Problemas de hardware e impressoras',
        ]);

        Department::create([
            'name' => 'Infraestrutura',
            'description' => 'Problemas de rede',
        ]);

        Department::create([
            'name' => 'Sistemas',
            'description' => 'Problemas em aplicações',
        ]);
    }
}
