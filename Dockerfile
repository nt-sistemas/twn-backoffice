FROM php:8.3-fpm

# 1. Instalar dependências de sistema e extensões PHP
RUN apt-get update && apt-get install -y \
    git curl libpng-dev libonig-dev libxml2-dev zip unzip libzip-dev libicu-dev \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip intl \
    && pecl install redis && docker-php-ext-enable redis

# 2. Instalar Composer oficial
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 3. Definir diretório de trabalho
WORKDIR /var/www

# 4. Copiar o código do projeto para dentro da imagem
COPY . .

# 5. Instalar as dependências do Laravel (O que resolve o erro do autoload)
RUN composer install --no-dev --optimize-autoloader --no-interaction

# 6. Ajustar permissões para o usuário do PHP
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

EXPOSE 9000
CMD ["php-fpm"]