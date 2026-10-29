FROM php:8.5-apache

RUN apt-get update && apt-get install -y \
    libsqlite3-dev \
    libpq-dev \
    unzip \
    git \
    && docker-php-ext-install pdo pdo_sqlite pdo_pgsql \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN a2enmod rewrite

ENV APACHE_DOCUMENT_ROOT=/var/www/html/public

RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' \
    /etc/apache2/sites-available/*.conf

RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' \
    /etc/apache2/apache2.conf

WORKDIR /var/www/html
COPY . .

RUN composer install \
    --no-dev \
    --optimize-autoloader \
    --no-scripts

RUN mkdir -p \
    storage/framework/cache \
    storage/framework/sessions \
    storage/framework/views \
    bootstrap/cache \
    database

RUN touch database/database.sqlite

RUN chown -R www-data:www-data \
    storage \
    bootstrap/cache \
    database

EXPOSE 80

CMD ["sh", "-c", "php artisan migrate --force && php artisan db:seed --force && apache2-foreground"]