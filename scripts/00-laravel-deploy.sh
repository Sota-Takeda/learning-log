#!/usr/bin/env bash
set -e

echo "Running composer"
composer install --no-dev --working-dir=/var/www/html --optimize-autoloader --no-interaction

echo "Clearing caches..."
php artisan config:clear || true
php artisan route:clear || true
php artisan view:clear || true

echo "Caching config..."
php artisan config:cache

echo "Caching routes..."
php artisan route:cache || true

echo "Running migrations..."
php artisan migrate --force -n
