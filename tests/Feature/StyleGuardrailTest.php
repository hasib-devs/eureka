<?php

// Fails when a migrated view directory regresses to inline styling.
// As each surface is migrated, add its directory (relative to resources/views) here.
$migratedDirs = [
    // 'auth',
];

it('keeps migrated view directories free of inline styles and <style> blocks', function () use ($migratedDirs) {
    $offenders = [];

    foreach ($migratedDirs as $dir) {
        $base = resource_path('views/'.$dir);
        if (! is_dir($base)) {
            continue;
        }

        $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($base));
        foreach ($files as $file) {
            if (! $file->isFile() || ! str_ends_with($file->getFilename(), '.blade.php')) {
                continue;
            }
            if (preg_match('/style="|<style/i', file_get_contents($file->getPathname()))) {
                $offenders[] = $file->getPathname();
            }
        }
    }

    expect($offenders)->toBeEmpty();
});
