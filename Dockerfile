FROM dunglas/frankenphp:php8.3

RUN install-php-extensions \
    gd \
    pdo_mysql \
    mbstring \
    fileinfo \
    curl \
    zip \
    bcmath \
    exif

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app

COPY composer.json composer.lock ./

RUN composer install \
    --no-interaction \
    --prefer-dist \
    --optimize-autoloader \
    --no-dev \
    --no-scripts

COPY . .

RUN composer dump-autoload --optimize --no-dev && php artisan package:discover --ansi

RUN apt-get update && apt-get install -y curl \
    && curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs

RUN npm install
RUN npm run build

RUN mkdir -p \
    storage/framework/cache \
    storage/framework/sessions \
    storage/framework/views \
    storage/logs \
    bootstrap/cache

RUN chmod -R 775 storage bootstrap/cache

RUN php artisan config:cache
RUN php artisan route:cache
RUN php artisan view:cache

EXPOSE 80

CMD ["frankenphp", "php-server", "--listen", ":80", "--root", "/app/public"]