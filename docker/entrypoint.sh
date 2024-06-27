#!/bin/bash

# Ensure the script exits on error
set -e

echo "Starting entrypoint script..."

# Check if vendor directory exists; if not, run composer install
if [ ! -d "vendor" ]; then
    echo "Installing Composer dependencies..."
    composer install --no-ansi --no-dev --no-interaction --no-plugins --no-progress --no-scripts --optimize-autoloader
fi

# Check if .env file exists; if not, copy the appropriate .env file
if [ ! -f ".env" ]; then
    echo "Creating env file for env $APP_ENV"
    cp .env.example .env
    case "$APP_ENV" in
    "local")
        echo "Copying .env.example ... "
        cp .env.example .env
    ;;
    "prod")
        echo "Copying .env.prod ... "
        cp .env.prod .env
    ;;
    esac
else
    echo "env file exists."
fi

# Run migrations
echo "Running migrations..."
php artisan migrate --force || { echo 'Migration failed' ; exit 1; }

# Clear caches
echo "Clearing caches..."
php artisan optimize:clear

# Fix files ownership
echo "Fixing file ownership..."
chown -R www-data .
chown -R www-data /app/storage
chown -R www-data /app/storage/logs
chown -R www-data /app/storage/framework
chown -R www-data /app/storage/framework/sessions
chown -R www-data /app/bootstrap
chown -R www-data /app/bootstrap/cache

# Set correct permissions
echo "Setting file permissions..."
chmod -R 775 /app/storage
chmod -R 775 /app/storage/logs
chmod -R 775 /app/storage/framework
chmod -R 775 /app/storage/framework/sessions
chmod -R 775 /app/bootstrap
chmod -R 775 /app/bootstrap/cache

# Start PHP-FPM and Nginx
echo "Starting services..."
php-fpm -D
nginx -g "daemon off;"
