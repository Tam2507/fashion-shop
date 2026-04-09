#!/bin/bash

export PORT=${PORT:-8080}

cd /var/www/html

# Tạo .env
if [ ! -f .env ]; then
    cp .env.example .env
fi

# App key
php artisan key:generate --force

# Tạo thư mục cần thiết
mkdir -p storage/framework/views storage/framework/cache storage/framework/sessions
chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache

# Migration (bỏ qua lỗi)
php artisan migrate --force 2>&1 | grep -v "already exists" || true

# Storage link
php artisan storage:link 2>/dev/null || true

# Config cache
php artisan config:cache 2>/dev/null || true
php artisan route:cache 2>/dev/null || true

# Set Apache port
sed -i "s/\${PORT}/$PORT/g" /etc/apache2/sites-available/000-default.conf
sed -i "s/\${PORT}/$PORT/g" /etc/apache2/ports.conf

# Start Apache
apache2-foreground
