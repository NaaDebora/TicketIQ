<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Department;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $support = Department::where('name', 'Suporte Técnico')->first();
        $infra = Department::where('name', 'Infraestrutura')->first();
        $systems = Department::where('name', 'Sistemas')->first();

        $categories = [
            [
                'name' => 'Hardware',
                'description' => 'Problemas físicos em equipamentos.',
                'department_id' => $support->id,
            ],
            [
                'name' => 'Rede',
                'description' => 'Problemas de internet, conexão ou rede interna.',
                'department_id' => $infra->id,
            ],
            [
                'name' => 'Sistema',
                'description' => 'Erros em sistemas internos ou aplicações.',
                'department_id' => $systems->id,
            ],
            [
                'name' => 'Impressora',
                'description' => 'Problemas com impressão ou equipamentos de impressão.',
                'department_id' => $support->id,
            ],
            [
                'name' => 'Email',
                'description' => 'Problemas relacionados a e-mail.',
                'department_id' => $systems->id,
            ],
            [
                'name' => 'Acesso',
                'description' => 'Problemas com login, senha ou permissões.',
                'department_id' => $systems->id,
            ],
            [
                'name' => 'Outro',
                'description' => 'Solicitações que não se encaixam nas demais categorias.',
                'department_id' => null,
            ],
        ];

        foreach ($categories as $category) {
            Category::updateOrCreate(
                ['name' => $category['name']],
                $category
            );
        }
    }
}
