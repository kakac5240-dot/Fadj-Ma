FROM php:8.3-apache

# 1. Installer les dépendances système indispensables
RUN apt-get update && apt-get install -y \
    libsqlite3-dev \
    unzip \
    git \
    && docker-php-ext-install pdo pdo_sqlite

# 2. Installer Composer dès le début
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 3. Activer la réécriture Apache pour les routes Laravel
RUN a2enmod rewrite

# 4. Pointer le serveur vers le dossier public de Laravel
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf

# 5. Copier les fichiers du projet
WORKDIR /var/www/html
COPY . .

# 6. Nettoyage complet des caches locaux susceptibles de bloquer
RUN rm -rf bootstrap/cache/*.php storage/framework/views/*.php

# 7. CORRECTION RADICALE : Ajouter --no-scripts pour empêcher l'erreur de syntaxe au build
RUN composer install --no-dev --optimize-autoloader --ignore-platform-reqs --no-scripts

# 8. Créer les dossiers de stockage et appliquer les permissions
RUN mkdir -p storage/framework/cache storage/framework/sessions storage/framework/views database
RUN chown -R www-data:www-data storage database


EXPOSE 80

# Exécuter les migrations et démarrer le serveur Apache
CMD php artisan migrate --force && php artisan db:seed --force && apache2-foreground
