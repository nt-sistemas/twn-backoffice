# ESTÁGIO 1: Compilar CSS/JS (Node)
FROM node:20-slim AS assets-builder
WORKDIR /app
COPY . .
RUN npm install && npm run build

# ESTÁGIO 2: Servir a Aplicação (PHP)
FROM php:8.3-fpm

# Instalar extensões PHP essenciais
RUN apt-get update && apt-get install -y \
    git curl libpng-dev libonig-dev libxml2-dev zip unzip libzip-dev libicu-dev \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip intl \
    && pecl install redis && docker-php-ext-enable redis

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html
COPY . .

# Copiar os arquivos compilados (CSS/JS) do estágio anterior
COPY --from=assets-builder /app/public/build ./public/build

# Instalar dependências PHP
RUN composer install --no-dev --optimize-autoloader --no-interaction

RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

EXPOSE 9000
CMD ["php-fpm"]
