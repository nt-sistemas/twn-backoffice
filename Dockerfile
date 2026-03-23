FROM php:8.3-fpm

# 1. Instalar dependências de sistema e extensões PHP
RUN apt-get update && apt-get install -y \
    git curl libpng-dev libonig-dev libxml2-dev zip unzip libzip-dev libicu-dev \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip intl \
    && pecl install redis && docker-php-ext-enable redis

# 2. Instalar Composer oficial
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 3. Configurar diretório de trabalho
WORKDIR /var/www/html

# 4. Copiar o código (Garante que o ponto '.' pegue a raiz do seu projeto)
COPY . .

# 5. Instalar dependências e gerar autoload
RUN composer install --no-dev --optimize-autoloader --no-interaction

# 6. Permissões para o usuário www-data
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# 7. Teste de sanidade (Verifica se o artisan existe na imagem)
RUN ls -la /var/www/html/artisan

EXPOSE 9000
CMD ["php-fpm"]
