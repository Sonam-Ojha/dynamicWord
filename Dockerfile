FROM php:8.2-cli

WORKDIR /var/www

COPY . .

RUN apt-get update && apt-get install -y \
    unzip git curl libzip-dev zip \
    && docker-php-ext-install zip

# Composer install
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN composer install --no-dev --optimize-autoloader
RUN php artisan config:clear
RUN php artisan cache:clear
CMD php -S 0.0.0.0:10000 -t public