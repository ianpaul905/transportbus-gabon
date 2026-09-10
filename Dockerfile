FROM php:8.2-apache

# Extensions PHP nécessaires (MySQL local + PostgreSQL/Neon)
RUN apt-get update && apt-get install -y --no-install-recommends \
        curl libpq-dev libzip-dev libpng-dev libjpeg62-turbo-dev libfreetype-dev \
        libz-dev libicu-dev icu-devtools libxml2-dev libsqlite3-dev \
        git unzip \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo_mysql pdo_pgsql pgsql gd zip intl pdo_sqlite bcmath opcache \
    && rm -rf /var/lib/apt/lists/*

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Node.js (pour Vite/front-end)
RUN curl -fsSL https://deb.nodesource.com/setup_22.x | bash - \
    && apt-get install -y --no-install-recommends nodejs \
    && rm -rf /var/lib/apt/lists/*

ENV COMPOSER_ALLOW_SUPERUSER=1

# Réglages par défaut (Render applique les env secrets : DATABASE_URL, APP_KEY)
ENV APP_NAME="TransportBus Gabon" \
    APP_ENV=production \
    APP_DEBUG=false \
    APP_URL=https://transportbus-gabon.onrender.com \
    APP_LOCALE=fr \
    APP_FAKER_LOCALE=fr_FR \
    DB_CONNECTION=pgsql \
    LOG_CHANNEL=stderr \
    SESSION_DRIVER=database \
    QUEUE_CONNECTION=sync

WORKDIR /var/www/html

# Copie de l'application
COPY . .

# Dépendances production
RUN composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader

# Compilation des assets front-end (Tailwind/Vite)
RUN npm ci --no-audit --no-fund && npm run build

# Permissions
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && a2enmod rewrite headers

# Apache : écoute sur le port fourni par Render ($PORT, défaut 80)
RUN if [ -n "$PORT" ]; then \
        sed -i -e "s/^Listen .*/Listen $PORT/" /etc/apache2/ports.conf; \
    fi

# Racine web = public/ (Laravel)
COPY docker/000-default.conf /etc/apache2/sites-available/000-default.conf
RUN a2dissite 000-default && a2ensite 000-default

EXPOSE 80

COPY docker-entrypoint.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

ENTRYPOINT ["docker-entrypoint.sh"]
CMD ["apache2-foreground"]