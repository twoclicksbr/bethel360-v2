<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Stancl\Tenancy\Database\Models\Domain;

// Initialize tenant context
$tenant = \App\Models\Tenant::find('beth1');
tenancy()->initialize($tenant);

// Count records in each table
$counts = [
    'modules' => \App\Models\Module::count(),
    'statuses' => \App\Models\Status::count(),
    'roles' => \App\Models\Role::count(),
    'genders' => \App\Models\Gender::count(),
    'features' => \App\Models\Feature::count(),
    'relationships' => \App\Models\Relationship::count(),
    'presence_methods' => \App\Models\PresenceMethod::count(),
    'finance_types' => \App\Models\FinanceType::count(),
    'finance_categories' => \App\Models\FinanceCategory::count(),
    'payment_methods' => \App\Models\PaymentMethod::count(),
    'type_addresses' => \App\Models\TypeAddress::count(),
    'type_contacts' => \App\Models\TypeContact::count(),
    'type_documents' => \App\Models\TypeDocument::count(),
];

echo "\n✅ TENANT beth1 - Seeded Data:\n";
echo str_repeat("=", 50) . "\n";
foreach ($counts as $table => $count) {
    echo sprintf("%-25s: %3d records\n", ucfirst(str_replace('_', ' ', $table)), $count);
}
echo str_repeat("=", 50) . "\n";
echo "Total: " . array_sum($counts) . " records\n\n";
