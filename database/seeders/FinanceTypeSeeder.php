<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FinanceTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $financeTypes = [
            [
                'slug' => 'entrada',
                'name' => 'Entrada',
                'description' => 'Receitas da igreja',
                'operation' => 'income',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'slug' => 'saida',
                'name' => 'Saída',
                'description' => 'Despesas da igreja',
                'operation' => 'expense',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('finance_types')->insert($financeTypes);
    }
}
