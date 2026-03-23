FROM php:8.3-fpm

# 1. Instalar Nginx e extensões PHP (Base Debian)
RUN apt-get update && apt-get install -y \
    nginx git curl libpng-dev libonig-dev libxml2-dev zip unzip libzip-dev libicu-dev \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip intl \
    && pecl install redis && docker-php-ext-enable redis

# 2. Configurar Nginx (Injetando sua config personalizada)
# Certifique-se de que o arquivo docker/nginx.conf existe no seu PC
COPY docker/nginx.conf /etc/nginx/sites-available/default
RUN ln -sf /etc/nginx/sites-available/default /etc/nginx/sites-enabled/default

# 3. Preparar o código
WORKDIR /var/www/html
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
COPY . .
RUN composer install --no-dev --optimize-autoloader --no-interaction

# 4. Ajustar permissões para o Laravel
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# 5. Script para iniciar Nginx e PHP-FPM em primeiro plano
RUN echo "#!/bin/sh\nservice nginx start && php-fpm" > /start.sh && chmod +x /start.sh

EXPOSE 80 9000
CMD ["/start.sh"]
