FROM php:8.3-fpm

# 1. Instalar dependências de sistema e preparar repositório do Node.js
RUN apt-get update && apt-get install -y \
    nginx git curl libpng-dev libonig-dev libxml2-dev zip unzip libzip-dev libicu-dev \
    && curl -fsSL https://deb.nodesource.com | bash - \
    && apt-get install -y nodejs

# 2. Instalar extensões PHP e Redis
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip intl \
    && pecl install redis && docker-php-ext-enable redis

# 3. Configurar Nginx (Injetando sua config)
COPY docker/nginx.conf /etc/nginx/sites-available/default
RUN ln -sf /etc/nginx/sites-available/default /etc/nginx/sites-enabled/default

# 4. Preparar o código e Composer
WORKDIR /var/www/html
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
COPY . .

# 5. Instalar dependências PHP, JS e gerar o build (Vite/Tailwind)
# O --frozen-lockfile ou --no-interaction evitam travamentos no GitHub Actions
RUN composer install --no-dev --optimize-autoloader --no-interaction \
    && npm install \
    && npm run build

# 6. Ajustar permissões para o Laravel
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# 7. Script de inicialização (Nginx + PHP-FPM)
RUN echo "#!/bin/sh\nservice nginx start && php-fpm" > /start.sh && chmod +x /start.sh

EXPOSE 80 9000
CMD ["/start.sh"]
