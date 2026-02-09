<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TypeAddressSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $typeAddresses = [
            [
                'slug' => 'residencial',
                'name' => 'Residencial',
                'description' => 'Endereço residencial',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'slug' => 'comercial',
                'name' => 'Comercial',
                'description' => 'Endereço comercial',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'slug' => 'grupo',
                'name' => 'Grupo',
                'description' => 'Endereço de célula/grupo pequeno',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'slug' => 'sede',
                'name' => 'Sede',
                'description' => 'Endereço da sede/campus da igreja',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'slug' => 'evento',
                'name' => 'Evento',
                'description' => 'Endereço temporário de evento',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'slug' => 'entrega',
                'name' => 'Entrega',
                'description' => 'Endereço de entrega alternativo',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('type_addresses')->insert($typeAddresses);
    }
}
