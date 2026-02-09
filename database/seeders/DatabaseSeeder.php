<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * Este seeder detecta automaticamente se está rodando no contexto central ou tenant
     * e executa os seeders apropriados.
     */
    public function run(): void
    {
        // Se estamos em contexto de tenant, executar TenantSeeder
        if (tenancy()->initialized) {
            $this->call([
                TenantSeeder::class,
            ]);
            return;
        }

        // Banco central: você pode popular dados centrais aqui se necessário
        // Exemplo: criar tenants de teste, domínios, etc.
    }
}
