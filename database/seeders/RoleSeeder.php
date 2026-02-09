<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $modules = DB::table('modules')->pluck('id', 'slug');

        $roles = [
            // Central de Vidas
            [
                'module_id' => $modules['central-de-vidas'],
                'slug' => 'membro',
                'name' => 'Membro',
                'description' => 'Membro da igreja',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'module_id' => $modules['central-de-vidas'],
                'slug' => 'visitante',
                'name' => 'Visitante',
                'description' => 'Visitante da igreja',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'module_id' => $modules['central-de-vidas'],
                'slug' => 'lider',
                'name' => 'Líder',
                'description' => 'Líder de ministério ou grupo',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'module_id' => $modules['central-de-vidas'],
                'slug' => 'pastor',
                'name' => 'Pastor',
                'description' => 'Pastor da igreja',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'module_id' => $modules['central-de-vidas'],
                'slug' => 'admin',
                'name' => 'Administrador',
                'description' => 'Administrador do sistema',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Família
            [
                'module_id' => $modules['familia'],
                'slug' => 'responsavel',
                'name' => 'Responsável',
                'description' => 'Responsável legal pela criança',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'module_id' => $modules['familia'],
                'slug' => 'autorizado',
                'name' => 'Autorizado',
                'description' => 'Autorizado a retirar criança',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Conquistas Espirituais
            [
                'module_id' => $modules['conquistas-espirituais'],
                'slug' => 'participante',
                'name' => 'Participante',
                'description' => 'Participante em busca de conquistas',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'module_id' => $modules['conquistas-espirituais'],
                'slug' => 'mentor',
                'name' => 'Mentor',
                'description' => 'Mentor que acompanha a jornada',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Agenda Viva
            [
                'module_id' => $modules['agenda-viva'],
                'slug' => 'participante',
                'name' => 'Participante',
                'description' => 'Participante do evento',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'module_id' => $modules['agenda-viva'],
                'slug' => 'organizador',
                'name' => 'Organizador',
                'description' => 'Organizador do evento',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Gestão do Reino
            [
                'module_id' => $modules['gestao-do-reino'],
                'slug' => 'doador',
                'name' => 'Doador',
                'description' => 'Pessoa que doa',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'module_id' => $modules['gestao-do-reino'],
                'slug' => 'tesoureiro',
                'name' => 'Tesoureiro',
                'description' => 'Responsável pelas finanças',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'module_id' => $modules['gestao-do-reino'],
                'slug' => 'contador',
                'name' => 'Contador',
                'description' => 'Contador da igreja',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Servir Bem
            [
                'module_id' => $modules['servir-bem'],
                'slug' => 'voluntario',
                'name' => 'Voluntário',
                'description' => 'Pessoa disponível para servir',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'module_id' => $modules['servir-bem'],
                'slug' => 'coordenador',
                'name' => 'Coordenador',
                'description' => 'Coordenador de escalas',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'module_id' => $modules['servir-bem'],
                'slug' => 'lider-ministerio',
                'name' => 'Líder de Ministério',
                'description' => 'Líder do ministério',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Ministérios e Grupos (comum)
            [
                'module_id' => $modules['central-de-vidas'],
                'slug' => 'participante',
                'name' => 'Participante',
                'description' => 'Participante do ministério/grupo',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'module_id' => $modules['central-de-vidas'],
                'slug' => 'colaborador',
                'name' => 'Colaborador',
                'description' => 'Colaborador do ministério/grupo',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'module_id' => $modules['central-de-vidas'],
                'slug' => 'confidente',
                'name' => 'Confidente',
                'description' => 'Confidente habilitado (concluiu 30 Semanas)',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Painel da Liderança
            [
                'module_id' => $modules['painel-da-lideranca'],
                'slug' => 'visualizador',
                'name' => 'Visualizador',
                'description' => 'Pode visualizar relatórios',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'module_id' => $modules['painel-da-lideranca'],
                'slug' => 'analista',
                'name' => 'Analista',
                'description' => 'Pode analisar e exportar dados',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('roles')->insert($roles);
    }
}
