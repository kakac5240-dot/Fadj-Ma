FROM php:8.3-apache

# Installer les dépendances système nécessaires
RUN apt-get update && apt-get install -y \
    libsqlite3-dev \
    unzip \
    git \
    && docker-php-ext-install pdo pdo_sqlite

# ÉTAPE CORRIGÉE : Installer Composer AU DÉBUT
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Activer le module de réécriture d'Apache pour Laravel
RUN a2enmod rewrite

# Configurer le dossier racine d'Apache vers /public de Laravel
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf

# Définir le dossier de travail et copier les fichiers
# Définir le dossier de travail et copier les fichiers
WORKDIR /var/www/html
COPY . .

# Nettoyer les fichiers de cache locaux s'ils existent
RUN rm -rf bootstrap/cache/*.php

# Exécuter l'installation en mettant à jour l'autoloader
RUN composer install --no-dev --optimize-autoloader --ignore-platform-reqs

# Configurer les permissions pour SQLite et le stockage
RUN mkdir -p storage/framework/cache storage/framework/sessions storage/framework/views
RUN chown -R www-data:www-data storage database

EXPOSE 80
