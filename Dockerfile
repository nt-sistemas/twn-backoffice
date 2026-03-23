FROM php:8.3-fpm-alpine

# 1. Instalar Nginx e extensões PHP (Alpine é mais leve e estável)
RUN apk add --no-cache nginx git curl libpng-dev libxml2-dev zip unzip libzip-dev icu-dev oniguruma-dev \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip intl \
    && pecl install redis && docker-php-ext-enable redis

# 2. Configurar Nginx para rodar junto com PHP
COPY docker/nginx.conf /etc/nginx/http.d/default.conf

# 3. Preparar o código
WORKDIR /var/www/html
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
COPY . .
RUN composer install --no-dev --optimize-autoloader --no-interaction

# 4. Ajustar permissões
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# 5. Script para iniciar Nginx e PHP ao mesmo tempo
CMD ["sh", "-c", "nginx && php-fpm"]
