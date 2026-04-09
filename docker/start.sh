#!/bin/sh

PORT=${PORT:-8080}

# Tạo .env nếu chưa có
if [ ! -f /var/www/html/.env ]; then
    cp /var/www/html/.env.example /var/www/html/.env
fi

# Tạo app key
php artisan key:generate --force

# Tạo thư mục views cache nếu chưa có
mkdir -p /var/www/html/storage/framework/views
mkdir -p /var/www/html/storage/framework/cache
mkdir -p /var/www/html/storage/framework/sessions
chmod -R 775 /var/www/html/storage

# Chạy migration (bỏ qua lỗi bảng đã tồn tại)
php artisan migrate --force 2>/dev/null || true

# Storage link
php artisan storage:link 2>/dev/null || true

# Cache (bỏ view:cache vì hay lỗi)
php artisan config:cache
php artisan route:cache

# Cập nhật nginx port
sed -i "s/listen 8080;/listen $PORT;/" /etc/nginx/nginx.conf

# Khởi động PHP-FPM
php-fpm -D

# Khởi động Nginx
nginx -g "daemon off;"
