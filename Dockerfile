FROM php:8.2-fpm-alpine

RUN apk add --no-cache \
    nginx \
    git curl zip unzip \
    libpng-dev libzip-dev oniguruma-dev \
    && docker-php-ext-install pdo pdo_mysql gd zip bcmath mbstring

# Tăng giới hạn upload PHP
RUN echo "upload_max_filesize=20M" > /usr/local/etc/php/conf.d/uploads.ini \
    && echo "post_max_size=25M" >> /usr/local/etc/php/conf.d/uploads.ini \
    && echo "memory_limit=256M" >> /usr/local/etc/php/conf.d/uploads.ini

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY . .

RUN composer install --no-dev --optimize-autoloader --no-interaction

RUN mkdir -p storage/framework/views storage/framework/cache storage/framework/sessions \
    && chmod -R 777 storage bootstrap/cache

COPY docker/nginx.conf /etc/nginx/nginx.conf
COPY docker/start.sh /start.sh
RUN chmod +x /start.sh \
    && chown nobody:nobody /start.sh

EXPOSE 8080

CMD ["/start.sh"]
