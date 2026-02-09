<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GenderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $genders = [
            [
                'slug' => 'masculino',
                'name' => 'Masculino',
                'abbreviation' => 'M',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'slug' => 'feminino',
                'name' => 'Feminino',
                'abbreviation' => 'F',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'slug' => 'outro',
                'name' => 'Outro',
                'abbreviation' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('genders')->insert($genders);
    }
}
