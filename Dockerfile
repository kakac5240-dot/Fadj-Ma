FROM php:8.3-apache


# Installer les dépendances système et SQLite
RUN apt-get update && apt-get install -y \
    libsqlite3-dev \
    unzip \
    git \
    && docker-php-ext-install pdo pdo_sqlite

# Activer le module de réécriture d'Apache pour Laravel
RUN a2enmod rewrite

# Changer le dossier racine d'Apache vers le dossier /public de Laravel
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf

# Copier les fichiers du projet
WORKDIR /var/www/html
COPY . .

# Installer Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/compos
RUN composer install --no-dev --optimize-autoloader --ignore-platform-reqs


# Donner les permissions pour SQLite et les logs
RUN chown -R www-data:www-data storage database

EXPOSE 80
