<?php

// Drop tenant database
try {
    $pdo = new PDO('pgsql:host=127.0.0.1;port=5432;dbname=postgres', 'postgres', 'Millena2012@');
    $pdo->exec('DROP DATABASE IF EXISTS "tenantbeth1"');
    echo "✅ Database tenantbeth1 dropped successfully\n";
} catch (PDOException $e) {
    echo "⚠️  Could not drop database: " . $e->getMessage() . "\n";
}

// Delete tenant records from central database
try {
    $pdo = new PDO('pgsql:host=127.0.0.1;port=5432;dbname=bethel360_central', 'postgres', 'Millena2012@');
    $pdo->exec("DELETE FROM domains WHERE tenant_id = 'beth1'");
    $pdo->exec("DELETE FROM tenants WHERE id = 'beth1'");
    echo "✅ Tenant records deleted from central database\n";
} catch (PDOException $e) {
    echo "⚠️  Could not delete tenant records: " . $e->getMessage() . "\n";
}

echo "\n🎯 Ready for clean tenant creation!\n";
