#!/bin/sh

PORT=${PORT:-8080}

cd /var/www/html

# Tạo .env
[ ! -f .env ] && cp .env.example .env

php artisan key:generate --force
php artisan migrate --force 2>&1 || true
php artisan storage:link 2>/dev/null || true
php artisan config:cache 2>/dev/null || true
php artisan route:cache 2>/dev/null || true

# Set port vào nginx config
sed -i "s/__PORT__/$PORT/" /etc/nginx/nginx.conf

# Start php-fpm background
php-fpm -D

# Start nginx foreground
nginx -g "daemon off;"
