<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Seeder de exemplo com dados realistas para desenvolvimento/testes.
 *
 * Este seeder OPCIONAL cria dados de exemplo para facilitar o desenvolvimento.
 * NÃO é executado automaticamente - deve ser chamado manualmente.
 *
 * Uso:
 * php artisan tenants:seed --tenant=<tenant_id> --class=ExampleTenantDataSeeder
 */
class ExampleTenantDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Campus de exemplo
        $campusId = DB::table('campuses')->insertGetId([
            'name' => 'Campus Central',
            'slug' => 'campus-central',
            'description' => 'Sede principal da igreja',
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Endereço do Campus
        DB::table('addresses')->insert([
            'addressable_type' => 'App\Models\Campus',
            'addressable_id' => $campusId,
            'type_address_id' => DB::table('type_addresses')->where('slug', 'sede')->value('id'),
            'street' => 'Rua das Flores',
            'number' => '1000',
            'complement' => 'Prédio Principal',
            'neighborhood' => 'Centro',
            'city' => 'São Paulo',
            'state' => 'SP',
            'zip_code' => '01000-000',
            'country' => 'Brasil',
            'latitude' => '-23.5505',
            'longitude' => '-46.6333',
            'is_primary' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Contatos do Campus
        DB::table('contacts')->insert([
            [
                'contactable_type' => 'App\Models\Campus',
                'contactable_id' => $campusId,
                'type_contact_id' => DB::table('type_contacts')->where('slug', 'email')->value('id'),
                'value' => 'contato@igrejaxemplo.com.br',
                'is_primary' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'contactable_type' => 'App\Models\Campus',
                'contactable_id' => $campusId,
                'type_contact_id' => DB::table('type_contacts')->where('slug', 'whatsapp')->value('id'),
                'value' => '+5511999999999',
                'is_primary' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Ministério de exemplo: 30 Semanas
        $ministryId = DB::table('ministries')->insertGetId([
            'campus_id' => $campusId,
            'name' => '30 Semanas',
            'slug' => '30-semanas',
            'description' => 'Curso de formação de discípulos com duração de 30 semanas',
            'template' => 'padrao',
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Features do ministério 30 Semanas
        $features = [
            'gender' => 'misto',
            'age-range' => '18+',
            'modality' => 'hibrido',
            'cycle' => 'fechado',
            'mobility' => 'fechada',
            'profile' => 'aberto',
            'duration-weeks' => '30',
            'minimum-attendance' => '75',
            'completion' => true,
        ];

        foreach ($features as $featureSlug => $value) {
            $featureId = DB::table('features')->where('slug', $featureSlug)->value('id');
            if ($featureId) {
                DB::table('ministry_features')->insert([
                    'ministry_id' => $ministryId,
                    'feature_id' => $featureId,
                    'value' => is_bool($value) ? json_encode($value) : $value,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        // Grupo de exemplo dentro do ministério
        $groupId = DB::table('groups')->insertGetId([
            'ministry_id' => $ministryId,
            'name' => 'Turma 2026.1',
            'slug' => 'turma-2026-1',
            'description' => 'Primeira turma de 2026',
            'start_date' => now()->startOfMonth(),
            'end_date' => now()->startOfMonth()->addWeeks(30),
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Features do grupo (herda do ministério + adiciona capacidade)
        DB::table('group_features')->insert([
            'group_id' => $groupId,
            'feature_id' => DB::table('features')->where('slug', 'capacity')->value('id'),
            'value' => '50',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Ministério de exemplo: GDC (Grupo de Discipulado Celular)
        $ministryGdcId = DB::table('ministries')->insertGetId([
            'campus_id' => $campusId,
            'name' => 'GDC - Grupos de Discipulado Celular',
            'slug' => 'gdc',
            'description' => 'Células que se reúnem nas casas',
            'template' => 'padrao',
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Features do GDC
        $gdcFeatures = [
            'gender' => 'misto',
            'age-range' => 'all',
            'modality' => 'presencial',
            'cycle' => 'aberto',
            'mobility' => 'livre',
            'profile' => 'aberto',
        ];

        foreach ($gdcFeatures as $featureSlug => $value) {
            $featureId = DB::table('features')->where('slug', $featureSlug)->value('id');
            if ($featureId) {
                DB::table('ministry_features')->insert([
                    'ministry_id' => $ministryGdcId,
                    'feature_id' => $featureId,
                    'value' => is_bool($value) ? json_encode($value) : $value,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        // Célula de exemplo
        $celulaId = DB::table('groups')->insertGetId([
            'ministry_id' => $ministryGdcId,
            'name' => 'Célula Zona Norte',
            'slug' => 'celula-zona-norte',
            'description' => 'Célula que se reúne na zona norte da cidade',
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Endereço da célula
        DB::table('addresses')->insert([
            'addressable_type' => 'App\Models\Group',
            'addressable_id' => $celulaId,
            'type_address_id' => DB::table('type_addresses')->where('slug', 'grupo')->value('id'),
            'street' => 'Rua dos Lírios',
            'number' => '500',
            'complement' => 'Casa 2',
            'neighborhood' => 'Vila Maria',
            'city' => 'São Paulo',
            'state' => 'SP',
            'zip_code' => '02100-000',
            'country' => 'Brasil',
            'latitude' => '-23.5000',
            'longitude' => '-46.6000',
            'is_primary' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Ministério Kids
        $ministryKidsId = DB::table('ministries')->insertGetId([
            'campus_id' => $campusId,
            'name' => 'Kids',
            'slug' => 'kids',
            'description' => 'Ministério infantil para crianças de 0 a 11 anos',
            'template' => 'padrao',
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Features do Kids
        $kidsFeatures = [
            'gender' => 'misto',
            'age-range' => '0-11',
            'modality' => 'presencial',
            'cycle' => 'aberto',
            'mobility' => 'livre',
            'profile' => 'aberto',
        ];

        foreach ($kidsFeatures as $featureSlug => $value) {
            $featureId = DB::table('features')->where('slug', $featureSlug)->value('id');
            if ($featureId) {
                DB::table('ministry_features')->insert([
                    'ministry_id' => $ministryKidsId,
                    'feature_id' => $featureId,
                    'value' => is_bool($value) ? json_encode($value) : $value,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        // Ministério Louvor
        $ministryLouvorId = DB::table('ministries')->insertGetId([
            'campus_id' => $campusId,
            'name' => 'Louvor',
            'slug' => 'louvor',
            'description' => 'Ministério de música e louvor',
            'template' => 'padrao',
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Features do Louvor
        $louvorFeatures = [
            'gender' => 'misto',
            'age-range' => '15-17',
            'modality' => 'presencial',
            'cycle' => 'aberto',
            'mobility' => 'fechada',
            'profile' => 'restrito',
        ];

        foreach ($louvorFeatures as $featureSlug => $value) {
            $featureId = DB::table('features')->where('slug', $featureSlug)->value('id');
            if ($featureId) {
                DB::table('ministry_features')->insert([
                    'ministry_id' => $ministryLouvorId,
                    'feature_id' => $featureId,
                    'value' => is_bool($value) ? json_encode($value) : $value,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        $this->command->info('✅ Dados de exemplo criados com sucesso!');
        $this->command->info('📍 Campus: Campus Central');
        $this->command->info('🎯 Ministérios: 30 Semanas, GDC, Kids, Louvor');
        $this->command->info('👥 Grupos: Turma 2026.1, Célula Zona Norte');
    }
}
