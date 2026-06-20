#!/usr/bin/env sh
set -eu

cd /var/www/html

if [ ! -f .env ]; then
    cp .env.example .env
fi

mkdir -p \
    database \
    storage/app/public \
    storage/framework/cache/data \
    storage/framework/sessions \
    storage/framework/testing \
    storage/framework/views \
    storage/logs \
    bootstrap/cache

if [ "${DB_CONNECTION:-sqlite}" = "sqlite" ]; then
    sqlite_path="${DB_DATABASE:-/var/www/html/database/database.sqlite}"

    if [ "$sqlite_path" = "database/database.sqlite" ]; then
        sqlite_path="/var/www/html/database/database.sqlite"
    fi

    touch "$sqlite_path"
fi

if [ "${DB_CONNECTION:-sqlite}" = "mysql" ]; then
    php -r '
        $host = getenv("DB_HOST") ?: "mysql";
        $port = getenv("DB_PORT") ?: "3306";
        $database = getenv("DB_DATABASE") ?: "profilkampung";
        $username = getenv("DB_USERNAME") ?: "profilkampung";
        $password = getenv("DB_PASSWORD") ?: "profilkampung";

        for ($i = 1; $i <= 60; $i++) {
            try {
                new PDO("mysql:host={$host};port={$port};dbname={$database}", $username, $password);
                exit(0);
            } catch (Throwable $e) {
                fwrite(STDERR, "Waiting for MySQL ({$i}/60)...\n");
                sleep(2);
            }
        }

        fwrite(STDERR, "MySQL is not ready.\n");
        exit(1);
    '
fi

if [ ! -f vendor/autoload.php ]; then
    composer install --no-interaction --prefer-dist
fi

if [ -z "$(php -r "\$env = file_exists('.env') ? parse_ini_file('.env', false, INI_SCANNER_RAW) : []; echo \$env['APP_KEY'] ?? '';")" ]; then
    php artisan key:generate --ansi
fi

if [ ! -e public/storage ]; then
    php artisan storage:link --ansi || true
fi

php artisan migrate --seed --force --ansi

if [ -f package.json ] && [ ! -f public/build/manifest.json ]; then
    if [ -f package-lock.json ]; then
        npm ci
    else
        npm install
    fi

    npm run build
fi

php artisan optimize:clear --ansi

exec "$@"
