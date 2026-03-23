FROM php:8.3-fpm

# 1. Instalar Nginx, extensões PHP e Node.js (via nodesource)
RUN apt-get update && apt-get install -y \
    nginx git curl libpng-dev libonig-dev libxml2-dev zip unzip libzip-dev libicu-dev \
    && curl -fsSL https://deb.nodesource.com | bash - \
    && apt-get install -y nodejs \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip intl \
    && pecl install redis && docker-php-ext-enable redis

# 2. Configurar Nginx
COPY docker/nginx.conf /etc/nginx/sites-available/default
RUN ln -sf /etc/nginx/sites-available/default /etc/nginx/sites-enabled/default

# 3. Preparar o código
WORKDIR /var/www/html
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
COPY . .

# 4. Instalar dependências PHP e JS e gerar o build do Filament/Vite
RUN composer install --no-dev --optimize-autoloader --no-interaction \
    && npm install \
    && npm run build

# 5. Ajustar permissões para o Laravel
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# 6. Script de inicialização
RUN echo "#!/bin/sh\nservice nginx start && php-fpm" > /start.sh && chmod +x /start.sh

EXPOSE 80 9000
CMD ["/start.sh"]
