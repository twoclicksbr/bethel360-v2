<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RelationshipSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $relationships = [
            [
                'slug' => 'conjuge',
                'name' => 'Cônjuge',
                'inverse_name' => 'Cônjuge',
                'inverse_slug' => 'conjuge',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'slug' => 'filho',
                'name' => 'Filho',
                'inverse_name' => 'Pai/Mãe',
                'inverse_slug' => 'pai-mae',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'slug' => 'filha',
                'name' => 'Filha',
                'inverse_name' => 'Pai/Mãe',
                'inverse_slug' => 'pai-mae',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'slug' => 'pai',
                'name' => 'Pai',
                'inverse_name' => 'Filho/Filha',
                'inverse_slug' => 'filho-filha',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'slug' => 'mae',
                'name' => 'Mãe',
                'inverse_name' => 'Filho/Filha',
                'inverse_slug' => 'filho-filha',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'slug' => 'irmao',
                'name' => 'Irmão',
                'inverse_name' => 'Irmão/Irmã',
                'inverse_slug' => 'irmao-irma',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'slug' => 'irma',
                'name' => 'Irmã',
                'inverse_name' => 'Irmão/Irmã',
                'inverse_slug' => 'irmao-irma',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'slug' => 'tio',
                'name' => 'Tio',
                'inverse_name' => 'Sobrinho/Sobrinha',
                'inverse_slug' => 'sobrinho-sobrinha',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'slug' => 'tia',
                'name' => 'Tia',
                'inverse_name' => 'Sobrinho/Sobrinha',
                'inverse_slug' => 'sobrinho-sobrinha',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'slug' => 'avo',
                'name' => 'Avô',
                'inverse_name' => 'Neto/Neta',
                'inverse_slug' => 'neto-neta',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'slug' => 'avoa',
                'name' => 'Avó',
                'inverse_name' => 'Neto/Neta',
                'inverse_slug' => 'neto-neta',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'slug' => 'primo',
                'name' => 'Primo',
                'inverse_name' => 'Primo/Prima',
                'inverse_slug' => 'primo-prima',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'slug' => 'prima',
                'name' => 'Prima',
                'inverse_name' => 'Primo/Prima',
                'inverse_slug' => 'primo-prima',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'slug' => 'sobrinho',
                'name' => 'Sobrinho',
                'inverse_name' => 'Tio/Tia',
                'inverse_slug' => 'tio-tia',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'slug' => 'sobrinha',
                'name' => 'Sobrinha',
                'inverse_name' => 'Tio/Tia',
                'inverse_slug' => 'tio-tia',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'slug' => 'neto',
                'name' => 'Neto',
                'inverse_name' => 'Avô/Avó',
                'inverse_slug' => 'avo-avoa',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'slug' => 'neta',
                'name' => 'Neta',
                'inverse_name' => 'Avô/Avó',
                'inverse_slug' => 'avo-avoa',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'slug' => 'sogro',
                'name' => 'Sogro',
                'inverse_name' => 'Genro/Nora',
                'inverse_slug' => 'genro-nora',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'slug' => 'sogra',
                'name' => 'Sogra',
                'inverse_name' => 'Genro/Nora',
                'inverse_slug' => 'genro-nora',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'slug' => 'genro',
                'name' => 'Genro',
                'inverse_name' => 'Sogro/Sogra',
                'inverse_slug' => 'sogro-sogra',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'slug' => 'nora',
                'name' => 'Nora',
                'inverse_name' => 'Sogro/Sogra',
                'inverse_slug' => 'sogro-sogra',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'slug' => 'cunhado',
                'name' => 'Cunhado',
                'inverse_name' => 'Cunhado/Cunhada',
                'inverse_slug' => 'cunhado-cunhada',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'slug' => 'cunhada',
                'name' => 'Cunhada',
                'inverse_name' => 'Cunhado/Cunhada',
                'inverse_slug' => 'cunhado-cunhada',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('relationships')->insert($relationships);
    }
}
