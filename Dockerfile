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

# 4. Copiar arquivos do Composer primeiro
COPY composer.json composer.lock ./

# 5. Instalar dependências (Garante a criação da pasta vendor)
RUN composer install --no-dev --no-scripts --no-autoloader --prefer-dist

# 6. Copiar o restante do código
COPY . .

# 7. Gerar o Autoload (Sem rodar comandos do Artisan aqui)
RUN composer dump-autoload --optimize

# 8. Ajustar permissões
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

EXPOSE 9000
CMD ["php-fpm"]
