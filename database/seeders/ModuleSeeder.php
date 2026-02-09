<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ModuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $modules = [
            [
                'slug' => 'central-de-vidas',
                'name' => 'Central de Vidas',
                'description' => 'Cadastro completo, timeline, QR Code',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'slug' => 'familia',
                'name' => 'Família',
                'description' => 'Rede social interna, vínculos familiares, segurança de crianças',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'slug' => 'conquistas-espirituais',
                'name' => 'Conquistas Espirituais',
                'description' => 'Marcos da jornada, habilitações',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'slug' => 'agenda-viva',
                'name' => 'Agenda Viva',
                'description' => 'Calendário dinâmico',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'slug' => 'gestao-do-reino',
                'name' => 'Gestão do Reino',
                'description' => 'Financeiro (dízimo, oferta, oferta farol)',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'slug' => 'servir-bem',
                'name' => 'Servir Bem',
                'description' => 'Escalas e voluntariado (aceite/recusa)',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'slug' => 'painel-da-lideranca',
                'name' => 'Painel da Liderança',
                'description' => 'Dashboard estratégico com indicadores',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('modules')->insert($modules);
    }
}
