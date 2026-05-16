<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "Checking tables...\n";
if (Schema::hasTable('ip_blocks')) {
    echo "Table 'ip_blocks' exists.\n";
} else {
    echo "Table 'ip_blocks' does NOT exist.\n";
}

if (Schema::hasColumn('orders', 'ip_address')) {
    echo "Column 'ip_address' exists in 'orders' table.\n";
} else {
    echo "Column 'ip_address' does NOT exist in 'orders' table.\n";
}
