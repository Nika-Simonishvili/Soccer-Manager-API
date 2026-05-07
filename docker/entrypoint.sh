#!/bin/bash
set -e

echo "==> Docker entrypoint started"

# Ensure .env exists
if [ ! -f .env ]; then
    echo "==> Creating .env from .env.example"
    cp .env.example .env
fi

# Update .env with Docker-specific values
if grep -q "^APP_URL=" .env; then
    sed -i 's|^APP_URL=.*|APP_URL=http://localhost:8000|' .env
else
    echo "APP_URL=http://localhost:8000" >> .env
fi

if grep -q "^DB_CONNECTION=" .env; then
    sed -i 's|^DB_CONNECTION=.*|DB_CONNECTION=mysql|' .env
else
    echo "DB_CONNECTION=mysql" >> .env
fi

if grep -q "^DB_HOST=" .env; then
    sed -i 's|^DB_HOST=.*|DB_HOST=db|' .env
else
    echo "DB_HOST=db" >> .env
fi

if grep -q "^DB_PORT=" .env; then
    sed -i 's|^DB_PORT=.*|DB_PORT=3306|' .env
else
    echo "DB_PORT=3306" >> .env
fi

if grep -q "^DB_DATABASE=" .env; then
    sed -i 's|^DB_DATABASE=.*|DB_DATABASE=soccer_manager|' .env
else
    echo "DB_DATABASE=soccer_manager" >> .env
fi

if grep -q "^DB_USERNAME=" .env; then
    sed -i 's|^DB_USERNAME=.*|DB_USERNAME=laravel|' .env
else
    echo "DB_USERNAME=laravel" >> .env
fi

if grep -q "^DB_PASSWORD=" .env; then
    sed -i 's|^DB_PASSWORD=.*|DB_PASSWORD=secret|' .env
else
    echo "DB_PASSWORD=secret" >> .env
fi

# Generate app key if not set
if ! grep -q "^APP_KEY=" .env || [ -z "$(grep "^APP_KEY=" .env | cut -d '=' -f2)" ]; then
    echo "==> Generating application key"
    php artisan key:generate --ansi
fi

# Wait for MySQL to be ready
echo "==> Waiting for MySQL..."
max_attempts=30
attempt=0
while ! php artisan tinker --execute="try { DB::connection()->getPdo(); echo 'OK'; } catch (Exception \$e) { echo 'WAIT'; }" | grep -q "OK"; do
    attempt=$((attempt + 1))
    if [ $attempt -ge $max_attempts ]; then
        echo "ERROR: MySQL did not become ready in time"
        exit 1
    fi
    echo "MySQL not ready yet (attempt $attempt/$max_attempts)..."
    sleep 2
done

echo "==> MySQL is ready"

# Run migrations
echo "==> Running migrations"
php artisan migrate --force --ansi

# Seed only if countries table is empty
if ! php artisan tinker --execute="echo \App\Models\Country::count() > 0 ? 'SEEDED' : 'EMPTY';" | grep -q "SEEDED"; then
    echo "==> Seeding database"
    php artisan db:seed --force --ansi
else
    echo "==> Database already seeded, skipping"
fi

echo "==> Setup complete. Starting application..."

# Execute the main command
exec "$@"
