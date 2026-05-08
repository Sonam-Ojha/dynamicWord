FROM php:8.2-cli

WORKDIR /var/www

COPY . .

# Install packages
RUN apt-get update && apt-get install -y \
    unzip git curl libzip-dev zip nodejs npm \
    && docker-php-ext-install zip pdo pdo_mysql

# Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# PHP dependencies
RUN composer install --no-dev --optimize-autoloader

# Node dependencies
RUN npm install

# Build Vite assets
RUN npm run build

# Permissions
RUN chmod -R 775 storage bootstrap/cache

EXPOSE 10000

CMD php artisan migrate --force && php artisan serve --host=0.0.0.0 --port=10000
