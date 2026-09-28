FROM php:8.3-apache

# 1. Installer les dépendances et extensions PHP nécessaires
RUN apt-get update && apt-get install -y \
    libsqlite3-dev \
    unzip \
    git \
    && docker-php-ext-install pdo pdo_sqlite

# 2. Installer Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 3. Activer la réécriture Apache
RUN a2enmod rewrite

# 4. Configurer le dossier racine d'Apache
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf

# 5. Copier les fichiers du projet
WORKDIR /var/www/html
COPY . .

# 6. Nettoyage et création des dossiers obligatoires
RUN rm -rf bootstrap/cache/*.php storage/framework/views/*.php
RUN mkdir -p storage/framework/cache storage/framework/sessions storage/framework/views database

# 7. Créer un fichier de base de données vide pour SQLite si absent
RUN touch database/database.sqlite

# 8. Installer les dépendances sans exécuter les scripts locaux problématiques
RUN composer install --no-dev --optimize-autoloader --ignore-platform-reqs --no-scripts

# 9. Appliquer les permissions Apache
RUN chown -R www-data:www-data storage database

EXPOSE 80

# 10. Script de démarrage sécurisé : migrations puis lancement du serveur
ENTRYPOINT ["/bin/sh", "-c", "php artisan migrate --force && php artisan db:seed --force && apache2-foreground"]
