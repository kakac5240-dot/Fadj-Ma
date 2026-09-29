FROM php:8.5-apache

# Installer les dépendances nécessaires
RUN apt-get update && apt-get install -y \
    libsqlite3-dev \
    unzip \
    git \
    && docker-php-ext-install pdo pdo_sqlite \
    && rm -rf /var/lib/apt/lists/*

# Installer Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Activer Apache Rewrite
RUN a2enmod rewrite

# Configurer Laravel / Apache
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public

RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' \
    /etc/apache2/sites-available/*.conf

RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' \
    /etc/apache2/apache2.conf

# Copier le projet
WORKDIR /var/www/html
COPY . .

# Installer les dépendances Laravel
RUN composer install \
    --no-dev \
    --optimize-autoloader \
    --no-scripts

# Préparer les dossiers Laravel
RUN mkdir -p \
    storage/framework/cache \
    storage/framework/sessions \
    storage/framework/views \
    bootstrap/cache \
    database

# Créer SQLite s'il n'existe pas
RUN touch database/database.sqlite

# Permissions
RUN chown -R www-data:www-data \
    storage \
    bootstrap/cache \
    database

# Apache
EXPOSE
CMD ["sh", "-c", "php artisan migrate --force && php artisan db:seed --force && apache2-foreground"]