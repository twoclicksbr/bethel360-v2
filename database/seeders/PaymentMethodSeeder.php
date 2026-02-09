<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PaymentMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $paymentMethods = [
            [
                'slug' => 'pix',
                'name' => 'PIX',
                'description' => 'Transferência instantânea via PIX',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'slug' => 'dinheiro',
                'name' => 'Dinheiro',
                'description' => 'Pagamento em espécie',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'slug' => 'cartao-debito',
                'name' => 'Cartão de Débito',
                'description' => 'Pagamento com cartão de débito',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'slug' => 'cartao-credito',
                'name' => 'Cartão de Crédito',
                'description' => 'Pagamento com cartão de crédito',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'slug' => 'boleto',
                'name' => 'Boleto',
                'description' => 'Boleto bancário',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'slug' => 'transferencia',
                'name' => 'Transferência',
                'description' => 'Transferência bancária (TED/DOC)',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'slug' => 'cheque',
                'name' => 'Cheque',
                'description' => 'Pagamento em cheque',
                'is_active' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('payment_methods')->insert($paymentMethods);
    }
}
