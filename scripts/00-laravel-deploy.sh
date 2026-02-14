cat > scripts/00-laravel-deploy.sh <<'EOF'
#!/usr/bin/env bash
set -e

echo "Running composer..."
composer install --no-dev --working-dir=/var/www/html --optimize-autoloader --no-interaction

echo "Clearing caches..."
php artisan config:clear || true
php artisan cache:clear  || true
php artisan route:clear  || true
php artisan view:clear   || true

echo "DB env check:"
php -r 'echo "DB_CONNECTION=" . (getenv("DB_CONNECTION") ?: "null") . PHP_EOL;'
php -r 'echo "DATABASE_URL=" . (getenv("DATABASE_URL") ? "set" : "null") . PHP_EOL;'
php artisan tinker --execute="dump(config('database.default')); dump(config('database.connections.'.config('database.default').'.database'));"

echo "Caching config..."
php artisan config:cache

echo "Running migrations..."
php artisan migrate --force -n
EOF
