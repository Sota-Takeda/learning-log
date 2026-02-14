#!/usr/bin/env sh
set -e

cd /app

echo "Running migrations..."
php artisan migrate --force -n

# webdevops/php-nginx の標準起動（nginx + php-fpm）
exec /entrypoint supervisord
