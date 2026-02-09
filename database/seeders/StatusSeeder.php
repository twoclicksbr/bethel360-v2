<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $modules = DB::table('modules')->pluck('id', 'slug');

        $statuses = [
            // Central de Vidas
            [
                'module_id' => $modules['central-de-vidas'],
                'slug' => 'ativo',
                'name' => 'Ativo',
                'description' => 'Pessoa ativa na plataforma',
                'color' => '#10b981',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'module_id' => $modules['central-de-vidas'],
                'slug' => 'inativo',
                'name' => 'Inativo',
                'description' => 'Pessoa inativa na plataforma',
                'color' => '#ef4444',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'module_id' => $modules['central-de-vidas'],
                'slug' => 'pendente',
                'name' => 'Pendente',
                'description' => 'Cadastro pendente de aprovação',
                'color' => '#f59e0b',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Família
            [
                'module_id' => $modules['familia'],
                'slug' => 'ativo',
                'name' => 'Ativo',
                'description' => 'Vínculo familiar ativo',
                'color' => '#10b981',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'module_id' => $modules['familia'],
                'slug' => 'pendente',
                'name' => 'Pendente',
                'description' => 'Aguardando aceite do vínculo',
                'color' => '#f59e0b',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'module_id' => $modules['familia'],
                'slug' => 'recusado',
                'name' => 'Recusado',
                'description' => 'Vínculo recusado',
                'color' => '#ef4444',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Conquistas Espirituais
            [
                'module_id' => $modules['conquistas-espirituais'],
                'slug' => 'pendente',
                'name' => 'Pendente',
                'description' => 'Aguardando conclusão',
                'color' => '#f59e0b',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'module_id' => $modules['conquistas-espirituais'],
                'slug' => 'concluido',
                'name' => 'Concluído',
                'description' => 'Conquista alcançada',
                'color' => '#10b981',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'module_id' => $modules['conquistas-espirituais'],
                'slug' => 'cancelado',
                'name' => 'Cancelado',
                'description' => 'Conquista cancelada',
                'color' => '#ef4444',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Agenda Viva
            [
                'module_id' => $modules['agenda-viva'],
                'slug' => 'agendado',
                'name' => 'Agendado',
                'description' => 'Evento agendado',
                'color' => '#3b82f6',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'module_id' => $modules['agenda-viva'],
                'slug' => 'em-andamento',
                'name' => 'Em Andamento',
                'description' => 'Evento acontecendo agora',
                'color' => '#10b981',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'module_id' => $modules['agenda-viva'],
                'slug' => 'concluido',
                'name' => 'Concluído',
                'description' => 'Evento finalizado',
                'color' => '#6b7280',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'module_id' => $modules['agenda-viva'],
                'slug' => 'cancelado',
                'name' => 'Cancelado',
                'description' => 'Evento cancelado',
                'color' => '#ef4444',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Gestão do Reino
            [
                'module_id' => $modules['gestao-do-reino'],
                'slug' => 'pendente',
                'name' => 'Pendente',
                'description' => 'Transação pendente',
                'color' => '#f59e0b',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'module_id' => $modules['gestao-do-reino'],
                'slug' => 'confirmado',
                'name' => 'Confirmado',
                'description' => 'Transação confirmada',
                'color' => '#10b981',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'module_id' => $modules['gestao-do-reino'],
                'slug' => 'cancelado',
                'name' => 'Cancelado',
                'description' => 'Transação cancelada',
                'color' => '#ef4444',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'module_id' => $modules['gestao-do-reino'],
                'slug' => 'estornado',
                'name' => 'Estornado',
                'description' => 'Transação estornada',
                'color' => '#8b5cf6',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Servir Bem
            [
                'module_id' => $modules['servir-bem'],
                'slug' => 'pendente',
                'name' => 'Pendente',
                'description' => 'Aguardando resposta',
                'color' => '#f59e0b',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'module_id' => $modules['servir-bem'],
                'slug' => 'aceito',
                'name' => 'Aceito',
                'description' => 'Convocação aceita',
                'color' => '#10b981',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'module_id' => $modules['servir-bem'],
                'slug' => 'recusado',
                'name' => 'Recusado',
                'description' => 'Convocação recusada',
                'color' => '#ef4444',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'module_id' => $modules['servir-bem'],
                'slug' => 'concluido',
                'name' => 'Concluído',
                'description' => 'Serviço concluído',
                'color' => '#6b7280',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Painel da Liderança - usa statuses gerais
            [
                'module_id' => $modules['painel-da-lideranca'],
                'slug' => 'ativo',
                'name' => 'Ativo',
                'description' => 'Recurso ativo',
                'color' => '#10b981',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'module_id' => $modules['painel-da-lideranca'],
                'slug' => 'inativo',
                'name' => 'Inativo',
                'description' => 'Recurso inativo',
                'color' => '#ef4444',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('statuses')->insert($statuses);
    }
}
