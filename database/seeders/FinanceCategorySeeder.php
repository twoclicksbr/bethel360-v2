<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FinanceCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $financeTypes = DB::table('finance_types')->pluck('id', 'slug');

        $financeCategories = [
            // ENTRADAS (Receitas)
            [
                'finance_type_id' => $financeTypes['entrada'],
                'slug' => 'dizimo',
                'name' => 'Dízimo',
                'description' => 'Dízimo dos membros (10% da renda)',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'finance_type_id' => $financeTypes['entrada'],
                'slug' => 'oferta',
                'name' => 'Oferta',
                'description' => 'Ofertas voluntárias',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'finance_type_id' => $financeTypes['entrada'],
                'slug' => 'oferta-farol',
                'name' => 'Oferta Farol',
                'description' => 'Oferta especial para projetos estratégicos',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'finance_type_id' => $financeTypes['entrada'],
                'slug' => 'doacao',
                'name' => 'Doação',
                'description' => 'Doações esporádicas',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'finance_type_id' => $financeTypes['entrada'],
                'slug' => 'primicia',
                'name' => 'Primícia',
                'description' => 'Primeiros frutos do ano',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'finance_type_id' => $financeTypes['entrada'],
                'slug' => 'evento',
                'name' => 'Evento',
                'description' => 'Receitas de eventos (congressos, retiros, etc)',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'finance_type_id' => $financeTypes['entrada'],
                'slug' => 'loja',
                'name' => 'Loja',
                'description' => 'Vendas de produtos (livros, CDs, camisetas, etc)',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // SAÍDAS (Despesas)
            [
                'finance_type_id' => $financeTypes['saida'],
                'slug' => 'aluguel',
                'name' => 'Aluguel',
                'description' => 'Aluguel de imóvel',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'finance_type_id' => $financeTypes['saida'],
                'slug' => 'energia',
                'name' => 'Energia',
                'description' => 'Conta de energia elétrica',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'finance_type_id' => $financeTypes['saida'],
                'slug' => 'agua',
                'name' => 'Água',
                'description' => 'Conta de água',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'finance_type_id' => $financeTypes['saida'],
                'slug' => 'internet',
                'name' => 'Internet',
                'description' => 'Conta de internet/telefone',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'finance_type_id' => $financeTypes['saida'],
                'slug' => 'salarios',
                'name' => 'Salários',
                'description' => 'Folha de pagamento',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'finance_type_id' => $financeTypes['saida'],
                'slug' => 'manutencao',
                'name' => 'Manutenção',
                'description' => 'Manutenção predial e equipamentos',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'finance_type_id' => $financeTypes['saida'],
                'slug' => 'material',
                'name' => 'Material',
                'description' => 'Material de expediente e consumo',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'finance_type_id' => $financeTypes['saida'],
                'slug' => 'transporte',
                'name' => 'Transporte',
                'description' => 'Combustível, manutenção de veículos',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'finance_type_id' => $financeTypes['saida'],
                'slug' => 'marketing',
                'name' => 'Marketing',
                'description' => 'Publicidade e divulgação',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'finance_type_id' => $financeTypes['saida'],
                'slug' => 'missoes',
                'name' => 'Missões',
                'description' => 'Investimento em missões',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'finance_type_id' => $financeTypes['saida'],
                'slug' => 'acoes-sociais',
                'name' => 'Ações Sociais',
                'description' => 'Projetos sociais e assistência',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'finance_type_id' => $financeTypes['saida'],
                'slug' => 'imposto',
                'name' => 'Impostos',
                'description' => 'Impostos e taxas',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'finance_type_id' => $financeTypes['saida'],
                'slug' => 'equipamento',
                'name' => 'Equipamento',
                'description' => 'Compra de equipamentos (som, projetor, etc)',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'finance_type_id' => $financeTypes['saida'],
                'slug' => 'evento',
                'name' => 'Evento',
                'description' => 'Despesas com eventos',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'finance_type_id' => $financeTypes['saida'],
                'slug' => 'diversos',
                'name' => 'Diversos',
                'description' => 'Outras despesas',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('finance_categories')->insert($financeCategories);
    }
}
