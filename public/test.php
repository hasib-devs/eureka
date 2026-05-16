<?php
echo "=== Basic PHP Test ===<br>";
echo "PHP Version: " . phpversion() . "<br>";
echo "Working Directory: " . getcwd() . "<br>";

// Check required extensions
$extensions = ['mbstring', 'openssl', 'pdo', 'pdo_mysql', 'tokenizer', 'xml', 'curl', 'json'];
foreach ($extensions as $ext) {
    echo extension_loaded($ext) ? "✓ $ext<br>" : "✗ $ext MISSING<br>";
}

// Check file permissions
$paths = [
    '../storage' => 'storage',
    '../storage/framework' => 'storage/framework', 
    '../storage/logs' => 'storage/logs',
    '../bootstrap/cache' => 'bootstrap/cache'
];

foreach ($paths as $path => $name) {
    if (!file_exists($path)) {
        echo "✗ $name - NOT EXISTS<br>";
    } elseif (!is_writable($path)) {
        echo "✗ $name - NOT WRITABLE<br>";
    } else {
        echo "✓ $name - OK<br>";
    }
}