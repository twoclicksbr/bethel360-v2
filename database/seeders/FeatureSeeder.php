<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FeatureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $features = [
            [
                'slug' => 'gender',
                'name' => 'Gênero',
                'description' => 'Restrição de gênero (masculino, feminino, misto)',
                'type' => 'select',
                'options' => json_encode(['masculino', 'feminino', 'misto']),
                'is_achievement' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'slug' => 'age-range',
                'name' => 'Faixa Etária',
                'description' => 'Faixa etária permitida',
                'type' => 'select',
                'options' => json_encode([
                    '0-11' => 'Crianças (0-11 anos)',
                    '12-14' => 'Adolescentes (12-14 anos)',
                    '15-17' => 'Jovens (15-17 anos)',
                    '18+' => 'Adultos (18+ anos)',
                    'all' => 'Todas as idades',
                ]),
                'is_achievement' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'slug' => 'modality',
                'name' => 'Modalidade',
                'description' => 'Modalidade do ministério/grupo (presencial, online, híbrido)',
                'type' => 'select',
                'options' => json_encode(['presencial', 'online', 'hibrido']),
                'is_achievement' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'slug' => 'cycle',
                'name' => 'Ciclo',
                'description' => 'Tipo de ciclo (aberto, fechado)',
                'type' => 'select',
                'options' => json_encode([
                    'aberto' => 'Aberto (permanente)',
                    'fechado' => 'Fechado (início e fim definidos)',
                ]),
                'is_achievement' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'slug' => 'mobility',
                'name' => 'Mobilidade',
                'description' => 'Mobilidade de participantes (livre, fechada)',
                'type' => 'select',
                'options' => json_encode([
                    'livre' => 'Livre (entrar a qualquer momento)',
                    'fechada' => 'Fechada (só no início do ciclo)',
                ]),
                'is_achievement' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'slug' => 'profile',
                'name' => 'Perfil',
                'description' => 'Perfil do ministério/grupo (aberto, restrito)',
                'type' => 'select',
                'options' => json_encode([
                    'aberto' => 'Aberto (qualquer pessoa)',
                    'restrito' => 'Restrito (requer aprovação)',
                ]),
                'is_achievement' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'slug' => 'campus-restriction',
                'name' => 'Restrição de Campus',
                'description' => 'Aceita participantes de outros campus',
                'type' => 'boolean',
                'options' => null,
                'is_achievement' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'slug' => 'capacity',
                'name' => 'Capacidade',
                'description' => 'Limite de participantes',
                'type' => 'number',
                'options' => null,
                'is_achievement' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'slug' => 'prerequisite',
                'name' => 'Pré-requisito',
                'description' => 'Ministério/grupo que deve ser concluído antes',
                'type' => 'relation',
                'options' => null,
                'is_achievement' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'slug' => 'location',
                'name' => 'Localização',
                'description' => 'Endereço do ministério/grupo',
                'type' => 'address',
                'options' => null,
                'is_achievement' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'slug' => 'is-confidential',
                'name' => 'Confidencial',
                'description' => 'Grupo confidencial (só membros veem)',
                'type' => 'boolean',
                'options' => null,
                'is_achievement' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'slug' => 'completion',
                'name' => 'Conquista Espiritual',
                'description' => 'Gera conquista ao concluir',
                'type' => 'boolean',
                'options' => null,
                'is_achievement' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'slug' => 'duration-weeks',
                'name' => 'Duração (semanas)',
                'description' => 'Duração em semanas do ciclo',
                'type' => 'number',
                'options' => null,
                'is_achievement' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'slug' => 'minimum-attendance',
                'name' => 'Presença Mínima',
                'description' => 'Percentual mínimo de presença para conclusão',
                'type' => 'number',
                'options' => null,
                'is_achievement' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('features')->insert($features);
    }
}
