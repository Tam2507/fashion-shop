FROM php:8.2-apache

# Cài extension
RUN apt-get update && apt-get install -y \
    git curl zip unzip libpng-dev libzip-dev libonig-dev \
    && docker-php-ext-install pdo pdo_mysql gd zip bcmath mbstring \
    && a2enmod rewrite \
    && a2dismod mpm_event 2>/dev/null || true \
    && a2enmod mpm_prefork 2>/dev/null || true

# Cài Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copy source
COPY . .

# Cài dependencies
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Phân quyền
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Apache config
RUN echo '<VirtualHost *:${PORT}>\n\
    DocumentRoot /var/www/html/public\n\
    <Directory /var/www/html/public>\n\
        AllowOverride All\n\
        Require all granted\n\
    </Directory>\n\
</VirtualHost>' > /etc/apache2/sites-available/000-default.conf

RUN echo 'Listen ${PORT}' > /etc/apache2/ports.conf

COPY docker/start.sh /start.sh
RUN chmod +x /start.sh

EXPOSE 8080

CMD ["/start.sh"]
