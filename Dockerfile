# Estágio 1: Node.js para build de assets
FROM node:20-slim AS node-build
WORKDIR /app
COPY . .
RUN npm install && npm run build

# Estágio 2: PHP + Nginx (Imagem Final)
FROM php:8.3-fpm

# 1. Instalar dependências de sistema e Nginx
RUN apt-get update && apt-get install -y \
    nginx git curl libpng-dev libonig-dev libxml2-dev zip unzip libzip-dev libicu-dev \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip intl \
    && pecl install redis && docker-php-ext-enable redis

# 2. Trazer o binário do Node e NPM do estágio anterior (caso precise rodar algo no container)
COPY --from=node:20-slim /usr/local/bin /usr/local/bin
COPY --from=node:20-slim /usr/local/lib/node_modules /usr/local/lib/node_modules

# 3. Configurar Nginx
COPY docker/nginx.conf /etc/nginx/sites-available/default
RUN ln -sf /etc/nginx/sites-available/default /etc/nginx/sites-enabled/default

# 4. Preparar o código
WORKDIR /var/www/html
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
COPY . .

# 5. Copiar os ASSETS buildados no estágio 1 (O que faz o Filament funcionar)
COPY --from=node-build /app/public/build ./public/build

# 6. Instalar dependências PHP
RUN composer install --no-dev --optimize-autoloader --no-interaction

# 7. Permissões
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# 8. Script de inicialização
RUN echo "#!/bin/sh\nservice nginx start && php-fpm" > /start.sh && chmod +x /start.sh

EXPOSE 80 9000
CMD ["/start.sh"]
