FROM php:8.3-fpm

# 1. Instalar dependências de sistema e extensões PHP
RUN apt-get update && apt-get install -y \
    git curl libpng-dev libonig-dev libxml2-dev zip unzip libzip-dev libicu-dev \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip intl \
    && pecl install redis && docker-php-ext-enable redis

# 2. Instalar Composer oficial
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 3. Definir diretório de trabalho PADRÃO
WORKDIR /var/www/html

# 4. Copiar TUDO para dentro da imagem
COPY . .

# 5. Instalar dependências (Garante a criação da pasta vendor no local correto)
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-progress

# 6. Permissões para o usuário www-data
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

EXPOSE 9000
CMD ["php-fpm"]
