#!/bin/sh
set -e

PORT=${PORT:-8080}

cd /var/www/html

[ ! -f .env ] && cp .env.example .env

# Fix permissions
chmod -R 777 storage bootstrap/cache

php artisan key:generate --force
php artisan migrate --force 2>&1 || true
php artisan storage:link 2>/dev/null || true
php artisan config:cache 2>/dev/null || true
php artisan route:cache 2>/dev/null || true

# Thay port trong nginx config
sed -i "s/__PORT__/$PORT/g" /etc/nginx/nginx.conf

# Start php-fpm background
php-fpm -D

# Đợi php-fpm sẵn sàng
sleep 1

# Start nginx foreground
echo "Starting nginx on port $PORT..."
nginx -t 2>&1
nginx -g "daemon off;" &
NGINX_PID=$!
echo "Nginx started with PID $NGINX_PID"
wait $NGINX_PID
