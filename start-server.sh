#!/bin/bash
cd /var/www/html

# Clear caches
php artisan config:clear
php artisan cache:clear

# Start Laravel server
exec php artisan serve --host=0.0.0.0 --port=8000

