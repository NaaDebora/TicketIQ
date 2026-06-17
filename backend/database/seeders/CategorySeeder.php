<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Hardware', 'description' => 'Problemas físicos em equipamentos.'],
            ['name' => 'Rede', 'description' => 'Problemas de internet, conexão ou rede interna.'],
            ['name' => 'Sistema', 'description' => 'Erros em sistemas internos ou aplicações.'],
            ['name' => 'Impressora', 'description' => 'Problemas com impressão ou equipamentos de impressão.'],
            ['name' => 'Email', 'description' => 'Problemas relacionados a e-mail.'],
            ['name' => 'Acesso', 'description' => 'Problemas com login, senha ou permissões.'],
            ['name' => 'Outro', 'description' => 'Solicitações que não se encaixam nas demais categorias.'],
        ];

        foreach ($categories as $category) {
            \App\Models\Category::create($category);
        }
    }
}
