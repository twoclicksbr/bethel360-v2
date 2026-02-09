<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class TenantSeeder extends Seeder
{
    /**
     * Seed the tenant database.
     *
     * Este seeder é executado automaticamente quando um novo tenant é criado.
     * Ele chama todos os seeders individuais na ordem correta para garantir
     * que as dependências de chave estrangeira sejam respeitadas.
     */
    public function run(): void
    {
        $this->call([
            // 1. Módulos base do sistema
            ModuleSeeder::class,

            // 2. Seeders que dependem de modules
            StatusSeeder::class,
            RoleSeeder::class,

            // 3. Seeders independentes
            GenderSeeder::class,
            FeatureSeeder::class,
            RelationshipSeeder::class,
            PresenceMethodSeeder::class,

            // 4. Seeders de finanças (finance_categories depende de finance_types)
            FinanceTypeSeeder::class,
            FinanceCategorySeeder::class,

            // 5. Seeders de tipos polimórficos
            PaymentMethodSeeder::class,
            TypeAddressSeeder::class,
            TypeContactSeeder::class,
            TypeDocumentSeeder::class,
        ]);
    }
}
