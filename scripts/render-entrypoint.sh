#!/usr/bin/env sh
set -e
cd /app

# ENV変更を確実に反映
php artisan config:clear || true
php artisan cache:clear || true
php artisan view:clear || true

php artisan migrate --force -n

exec /entrypoint supervisord
