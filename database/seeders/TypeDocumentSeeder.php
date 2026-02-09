<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TypeDocumentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $typeDocuments = [
            [
                'slug' => 'cpf',
                'name' => 'CPF',
                'description' => 'Cadastro de Pessoa Física',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'slug' => 'rg',
                'name' => 'RG',
                'description' => 'Registro Geral',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'slug' => 'cnh',
                'name' => 'CNH',
                'description' => 'Carteira Nacional de Habilitação',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'slug' => 'passaporte',
                'name' => 'Passaporte',
                'description' => 'Passaporte',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'slug' => 'certidao-nascimento',
                'name' => 'Certidão de Nascimento',
                'description' => 'Certidão de Nascimento',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'slug' => 'certidao-casamento',
                'name' => 'Certidão de Casamento',
                'description' => 'Certidão de Casamento',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'slug' => 'titulo-eleitor',
                'name' => 'Título de Eleitor',
                'description' => 'Título de Eleitor',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'slug' => 'ctps',
                'name' => 'CTPS',
                'description' => 'Carteira de Trabalho e Previdência Social',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'slug' => 'pis-pasep',
                'name' => 'PIS/PASEP',
                'description' => 'Programa de Integração Social / Programa de Formação do Patrimônio do Servidor Público',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'slug' => 'reservista',
                'name' => 'Certificado de Reservista',
                'description' => 'Certificado de Reservista',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('type_documents')->insert($typeDocuments);
    }
}
