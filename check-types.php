<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Initialize tenant context
$tenant = \App\Models\Tenant::find('beth1');
tenancy()->initialize($tenant);

echo "\n=== TYPE CONTACTS ===\n";
$typeContacts = \Illuminate\Support\Facades\DB::table('type_contacts')->get();
foreach ($typeContacts as $type) {
    echo "ID: {$type->id} | Slug: {$type->slug} | Name: {$type->name}\n";
}

echo "\n=== TYPE DOCUMENTS ===\n";
$typeDocuments = \Illuminate\Support\Facades\DB::table('type_documents')->get();
foreach ($typeDocuments as $type) {
    echo "ID: {$type->id} | Slug: {$type->slug} | Name: {$type->name}\n";
}

echo "\n";
