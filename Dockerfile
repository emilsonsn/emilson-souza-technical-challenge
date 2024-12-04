FROM php:8.2-fpm

# Instala dependências do sistema e o Node.js
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libonig-dev \
    libzip-dev \
    zip \
    unzip \
    git \
    curl \
    nodejs \
    npm

# Instalar dependências do Vue.js
RUN npm install -g npm@latest

# Instalar dependências do Composer
COPY --from=composer:2.6 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

COPY . /var/www

RUN npm install && npm run build

# Instalar dependências do PHP e Vue.js
RUN composer install --no-dev --optimize-autoloader && \
    php artisan optimize && \
    php artisan optimize:clear

RUN mkdir -p /var/www/storage/logs /var/www/storage/framework/cache/data \
    && chmod -R 775 /var/www/storage /var/www/bootstrap/cache /var/www/storage/logs \
    && chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache /var/www/storage/logs

EXPOSE 9000

CMD ["php-fpm"]