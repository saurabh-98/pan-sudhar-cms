#!/bin/sh

echo "Preparing Laravel directories..."

mkdir -p bootstrap/cache
mkdir -p storage/framework/cache
mkdir -p storage/framework/sessions
mkdir -p storage/framework/views
mkdir -p storage/logs

chmod -R 775 bootstrap/cache
chmod -R 775 storage

echo "Installing composer dependencies..."

composer install --no-dev --optimize-autoloader

echo "Clearing Laravel caches..."

php artisan optimize:clear || true

echo "Build completed."