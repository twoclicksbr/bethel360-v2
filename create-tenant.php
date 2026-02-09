<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Stancl\Tenancy\Database\Models\Tenant;
use Stancl\Tenancy\Database\Models\Domain;

// Criar tenant
$tenant = \App\Models\Tenant::create(['id' => 'beth1']);

// Criar domain
$domain = Domain::create([
    'domain' => 'beth1.bethel360-api.test',
    'tenant_id' => 'beth1',
]);

echo "✅ Tenant criado com sucesso!\n";
echo "   ID: {$tenant->id}\n";
echo "   Domain: {$domain->domain}\n";
