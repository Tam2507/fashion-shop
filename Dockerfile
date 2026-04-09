FROM php:8.2-fpm-alpine

# Cài extension cần thiết
RUN apk add --no-cache \
    nginx \
    nodejs \
    npm \
    git \
    curl \
    libpng-dev \
    libzip-dev \
    zip \
    unzip \
    && docker-php-ext-install pdo pdo_mysql gd zip bcmath

# Cài Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copy source code
COPY . .

# Cài dependencies
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Cài npm và build assets
RUN npm install && npm run build 2>/dev/null || true

# Phân quyền
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
RUN chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Copy nginx config
COPY docker/nginx.conf /etc/nginx/nginx.conf

# Tạo script khởi động
COPY docker/start.sh /start.sh
RUN chmod +x /start.sh

EXPOSE ${PORT:-8080}

CMD ["/start.sh"]
