# Comandos Úteis - Seeders Bethel360°

## 📦 Comandos Básicos

### Criar novo tenant (seeders executam automaticamente)
```bash
php artisan tenants:create
```

### Seedar tenant específico
```bash
php artisan tenants:seed --tenant=1
```

### Seedar todos os tenants
```bash
php artisan tenants:seed
```

### Seedar com seeder específico
```bash
php artisan tenants:seed --tenant=1 --class=ModuleSeeder
```

### Seedar com dados de exemplo (desenvolvimento)
```bash
php artisan tenants:seed --tenant=1 --class=ExampleTenantDataSeeder
```

---

## 🔄 Migrations + Seeders

### Rodar migrations e seeders em sequência
```bash
# Para tenant específico
php artisan tenants:migrate --tenant=1
php artisan tenants:seed --tenant=1

# Para todos os tenants
php artisan tenants:migrate
php artisan tenants:seed
```

### Refresh (limpar e recriar)
```bash
# ATENÇÃO: Apaga todos os dados!
php artisan tenants:migrate:fresh --tenant=1
php artisan tenants:seed --tenant=1
```

### Refresh com seed em um comando
```bash
# ATENÇÃO: Apaga todos os dados!
php artisan tenants:migrate:fresh --seed --tenant=1
```

---

## 🧪 Comandos para Testes

### Verificar quais seeders existem
```bash
php artisan db:seed --list
```

### Testar seeder individual (sem executar)
```bash
php artisan db:seed --class=ModuleSeeder --pretend
```

### Seedar banco central (não tenant)
```bash
php artisan db:seed
# ou
php artisan db:seed --class=DatabaseSeeder
```

---

## 🔍 Verificação de Dados

### Entrar no Tinker para verificar dados
```bash
php artisan tinker
```

Dentro do Tinker:
```php
// Listar todos os tenants
$tenants = \Stancl\Tenancy\Models\Tenant::all();

// Inicializar tenant
tenancy()->initialize($tenants->first());

// Verificar módulos
$modules = DB::table('modules')->get();

// Verificar features
$features = DB::table('features')->get();

// Contar registros por tabela
DB::table('modules')->count();
DB::table('statuses')->count();
DB::table('roles')->count();
DB::table('genders')->count();
DB::table('features')->count();
DB::table('relationships')->count();
DB::table('presence_methods')->count();
DB::table('finance_types')->count();
DB::table('finance_categories')->count();
DB::table('payment_methods')->count();
DB::table('type_addresses')->count();
DB::table('type_contacts')->count();
DB::table('type_documents')->count();

// Sair
exit
```

---

## 🧹 Limpeza de Cache

### Limpar cache após seedar
```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
```

### Limpar tudo
```bash
php artisan optimize:clear
```

---

## 📝 Scripts SQL Úteis

### Contar registros em todas as tabelas de seeds
```sql
-- Execute no banco do tenant
SELECT 'modules' as tabela, COUNT(*) as total FROM modules
UNION ALL
SELECT 'statuses', COUNT(*) FROM statuses
UNION ALL
SELECT 'roles', COUNT(*) FROM roles
UNION ALL
SELECT 'genders', COUNT(*) FROM genders
UNION ALL
SELECT 'features', COUNT(*) FROM features
UNION ALL
SELECT 'relationships', COUNT(*) FROM relationships
UNION ALL
SELECT 'presence_methods', COUNT(*) FROM presence_methods
UNION ALL
SELECT 'finance_types', COUNT(*) FROM finance_types
UNION ALL
SELECT 'finance_categories', COUNT(*) FROM finance_categories
UNION ALL
SELECT 'payment_methods', COUNT(*) FROM payment_methods
UNION ALL
SELECT 'type_addresses', COUNT(*) FROM type_addresses
UNION ALL
SELECT 'type_contacts', COUNT(*) FROM type_contacts
UNION ALL
SELECT 'type_documents', COUNT(*) FROM type_documents;
```

### Verificar slugs únicos
```sql
-- Verificar se há slugs duplicados em modules
SELECT slug, COUNT(*) FROM modules GROUP BY slug HAVING COUNT(*) > 1;

-- Verificar se há slugs duplicados em features
SELECT slug, COUNT(*) FROM features GROUP BY slug HAVING COUNT(*) > 1;
```

### Listar todos os módulos com seus status
```sql
SELECT
    m.name as modulo,
    s.name as status,
    s.color as cor
FROM modules m
LEFT JOIN statuses s ON s.module_id = m.id
ORDER BY m.name, s.name;
```

### Listar todas as features disponíveis
```sql
SELECT
    slug,
    name,
    type,
    is_required,
    is_achievement
FROM features
ORDER BY is_required DESC, name;
```

---

## 🐛 Troubleshooting

### Erro: "Class 'Database\Seeders\...' not found"
```bash
# Recarregar autoload
composer dump-autoload
```

### Erro: "Foreign key constraint fails"
```bash
# Verificar ordem dos seeders no TenantSeeder
# Garantir que tabelas pai são populadas antes das filhas
```

### Erro: "Duplicate entry for key 'slug'"
```bash
# Limpar dados existentes antes de re-seedar
php artisan tenants:migrate:fresh --tenant=1
php artisan tenants:seed --tenant=1
```

### Seeders não estão criando dados
```bash
# Verificar se as migrations foram executadas
php artisan tenants:migrate --tenant=1

# Verificar se o tenant foi inicializado corretamente
php artisan tenants:list
```

---

## 🎯 Workflow Recomendado

### Para Desenvolvimento
```bash
# 1. Criar tenant
php artisan tenants:create

# 2. Rodar migrations
php artisan tenants:migrate --tenant=1

# 3. Rodar seeders base
php artisan tenants:seed --tenant=1

# 4. Rodar seeders de exemplo (opcional)
php artisan tenants:seed --tenant=1 --class=ExampleTenantDataSeeder

# 5. Verificar dados
php artisan tinker
```

### Para Produção
```bash
# 1. Criar tenant
php artisan tenants:create

# 2. Rodar migrations
php artisan tenants:migrate --tenant=<tenant_id>

# 3. Rodar seeders base (APENAS base, não exemplo)
php artisan tenants:seed --tenant=<tenant_id>

# 4. Limpar cache
php artisan optimize:clear
```

### Para Testes Automatizados
```php
// No setUp() do teste
public function setUp(): void
{
    parent::setUp();

    // Criar tenant de teste
    $this->tenant = Tenant::factory()->create();
    tenancy()->initialize($this->tenant);

    // Rodar migrations
    $this->artisan('tenants:migrate', ['--tenant' => $this->tenant->id]);

    // Rodar seeders
    $this->seed(TenantSeeder::class);
}
```

---

## 📚 Referências

- [Laravel Seeding Documentation](https://laravel.com/docs/11.x/seeding)
- [Stancl/Tenancy Documentation](https://tenancyforlaravel.com/)
- [Bethel360° CLAUDE.md](../../CLAUDE.md)
- [Seeders README](README.md)
- [Seeders Summary](SEEDERS_SUMMARY.md)

---

**Última atualização:** 2026-02-09
