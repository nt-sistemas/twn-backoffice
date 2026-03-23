FROM php:8.3-fpm

# 1. Instalar dependências de sistema e Nginx
RUN apt-get update && apt-get install -y \
    nginx git curl libpng-dev libonig-dev libxml2-dev zip unzip libzip-dev libicu-dev

# 2. Instalar Node.js 22 (Método Manual Oficial para Debian/Ubuntu)
RUN mkdir -p /etc/apt/keyrings \
    && curl -fsSL https://deb.nodesource.com | gpg --dearmor -o /etc/apt/keyrings/nodesource.gpg \
    && echo "deb [signed-by=/etc/apt/keyrings/nodesource.gpg] https://deb.nodesource.com nodistro main" | tee /etc/apt/sources.list.d/nodesource.list \
    && apt-get update && apt-get install nodejs -y

# 3. Instalar extensões PHP e Redis
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip intl \
    && pecl install redis && docker-php-ext-enable redis

# 4. Configurar Nginx
COPY docker/nginx.conf /etc/nginx/sites-available/default
RUN ln -sf /etc/nginx/sites-available/default /etc/nginx/sites-enabled/default

# 5. Preparar o código
WORKDIR /var/www/html
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
COPY . .

# 6. Instalar dependências e Build (Vite/Tailwind)
RUN composer install --no-dev --optimize-autoloader --no-interaction \
    && npm install \
    && npm run build

# 7. Permissões
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# 8. Script de inicialização
RUN echo "#!/bin/sh\nservice nginx start && php-fpm" > /start.sh && chmod +x /start.sh

EXPOSE 80 9000
CMD ["/start.sh"]
