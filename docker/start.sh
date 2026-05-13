#!/bin/sh
set -e

PORT=${PORT:-8080}

cd /var/www/html

[ ! -f .env ] && cp .env.example .env

# Fix permissions
chmod -R 777 storage bootstrap/cache

# Xóa view cache cũ
rm -rf storage/framework/views/*.php 2>/dev/null || true

# Chỉ generate key nếu chưa có
if [ -z "$APP_KEY" ]; then
    php artisan key:generate --force
fi
php artisan migrate --force 2>&1 || true
# Đảm bảo sessions table tồn tại
php artisan session:table 2>/dev/null || true
php artisan migrate --force 2>&1 || true
php artisan storage:link 2>/dev/null || true
php artisan route:cache 2>/dev/null || true
php artisan view:clear 2>/dev/null || true

# Start queue worker in background để gửi mail bất đồng bộ
php artisan queue:work --tries=3 --timeout=60 --sleep=3 &

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
