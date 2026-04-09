FROM php:8.2-fpm-alpine

# Cài dependencies
RUN apk add --no-cache \
    nginx \
    git curl zip unzip \
    libpng-dev libzip-dev oniguruma-dev \
    && docker-php-ext-install pdo pdo_mysql gd zip bcmath mbstring

# Cài Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY . .

RUN composer install --no-dev --optimize-autoloader --no-interaction

RUN mkdir -p storage/framework/views storage/framework/cache storage/framework/sessions \
    && chown -R www-data:www-data /var/www/html \
    && chmod -R 775 storage bootstrap/cache

COPY docker/nginx.conf /etc/nginx/nginx.conf
COPY docker/start.sh /start.sh
RUN chmod +x /start.sh

EXPOSE 8080

CMD ["/start.sh"]
