#!/bin/bash

composer install --no-dev --optimize-autoloader --no-interaction --no-scripts
mkdir -p storage/framework/cache storage/framework/sessions storage/framework/views storage/logs bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache
php artisan config:clear
php artisan cache:clear
php artisan view:clear

# Publicar assets do USP Theme
php artisan vendor:publish --provider="Uspdev\UspTheme\ServiceProvider" --tag=assets --force

php artisan migrate --force
