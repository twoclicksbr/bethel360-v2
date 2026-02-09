<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TypeContactSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $typeContacts = [
            [
                'slug' => 'email',
                'name' => 'Email',
                'description' => 'Endereço de email',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'slug' => 'telefone',
                'name' => 'Telefone',
                'description' => 'Número de telefone fixo',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'slug' => 'whatsapp',
                'name' => 'WhatsApp',
                'description' => 'Número de WhatsApp',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'slug' => 'celular',
                'name' => 'Celular',
                'description' => 'Número de celular',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'slug' => 'instagram',
                'name' => 'Instagram',
                'description' => 'Perfil do Instagram',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'slug' => 'facebook',
                'name' => 'Facebook',
                'description' => 'Perfil do Facebook',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'slug' => 'twitter',
                'name' => 'Twitter',
                'description' => 'Perfil do Twitter/X',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'slug' => 'linkedin',
                'name' => 'LinkedIn',
                'description' => 'Perfil do LinkedIn',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'slug' => 'telegram',
                'name' => 'Telegram',
                'description' => 'Número/usuário do Telegram',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'slug' => 'website',
                'name' => 'Website',
                'description' => 'Website pessoal ou profissional',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('type_contacts')->insert($typeContacts);
    }
}
