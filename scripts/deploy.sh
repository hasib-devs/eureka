#!/usr/bin/env bash
set -euo pipefail

# Locate the correct PHP 8.5 binary for cPanel EasyApache 4
if command -v /usr/local/php85/bin/php &>/dev/null; then
    PHP=/usr/local/php85/bin/php
elif command -v /opt/cpanel/ea-php85/root/usr/bin/php &>/dev/null; then
    PHP=/opt/cpanel/ea-php85/root/usr/bin/php
else
    PHP=$(which php)
fi

COMPOSER="$PHP $(which composer 2>/dev/null || echo composer)"

echo "[deploy] PHP: $PHP"
echo "[deploy] Installing PHP dependencies..."
$COMPOSER install --no-dev --no-interaction --optimize-autoloader

echo "[deploy] Caching config, routes, views..."
$PHP artisan config:cache
$PHP artisan route:cache
$PHP artisan view:cache
$PHP artisan event:cache

echo "[deploy] Running migrations..."
$PHP artisan migrate --force

echo "[deploy] Restarting queue workers..."
$PHP artisan queue:restart

echo "[deploy] Creating storage symlink (safe if already exists)..."
$PHP artisan storage:link 2>/dev/null || true

echo "[deploy] Done."
