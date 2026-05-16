#!/bin/bash
set -e

# Fix storage/bootstrap permissions (artisan commands during build run as root)
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Wait for MySQL to accept connections
echo "Waiting for database..."
until php -r "
new PDO(
    'mysql:host=' . getenv('DB_HOST') . ';port=' . (getenv('DB_PORT') ?: '3306'),
    getenv('DB_USERNAME'),
    getenv('DB_PASSWORD')
);
" 2>/dev/null; do
    sleep 2
done
echo "Database ready."

# Generate app key if not already configured.
# Also export it so Apache inherits the value — docker-compose passes APP_KEY= (empty)
# from .env.example, which would otherwise prevent dotenv from reading the generated key.
if [ -z "$APP_KEY" ]; then
    grep -qE "^APP_KEY=.+" .env || php artisan key:generate --force
    export APP_KEY=$(grep "^APP_KEY=" .env | cut -d= -f2-)
fi

# Discover packages if cache not yet built
if [ ! -f bootstrap/cache/packages.php ]; then
    php artisan package:discover --ansi
fi

# Publish Backpack CRUD assets (CoreUI bundle, datatables, select2, etc.) if not yet published
if [ ! -d public/packages/backpack ]; then
    php artisan vendor:publish --provider="Backpack\CRUD\BackpackServiceProvider" --tag=public --force
fi

# Run migrations (idempotent)
php artisan migrate --force

# Seed the database on first run (when users table is empty)
USER_COUNT=$(php -r "
require '/var/www/html/vendor/autoload.php';
\$app = require '/var/www/html/bootstrap/app.php';
\$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
echo App\User::count();
")
if [ "$USER_COUNT" = "0" ]; then
    echo "Seeding database..."
    php artisan db:seed --force || echo "Seeding completed with non-fatal errors."
fi

exec "$@"
