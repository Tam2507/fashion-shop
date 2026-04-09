#!/bin/sh

# Railway cung cấp biến PORT, dùng nó hoặc mặc định 8080
PORT=${PORT:-8080}

# Tạo .env nếu chưa có
if [ ! -f /var/www/html/.env ]; then
    cp /var/www/html/.env.example /var/www/html/.env
fi

# Tạo app key nếu chưa có
php artisan key:generate --force

# Chạy migration
php artisan migrate --force

# Tạo storage link
php artisan storage:link 2>/dev/null || true

# Cache
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Cập nhật nginx port
sed -i "s/listen 8080;/listen $PORT;/" /etc/nginx/nginx.conf

# Khởi động PHP-FPM
php-fpm -D

# Khởi động Nginx
nginx -g "daemon off;"
