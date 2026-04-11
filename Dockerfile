# Stage 1: Build frontend assets
FROM node:20-alpine AS frontend

WORKDIR /app

COPY package*.json ./
RUN npm ci

COPY webpack.mix.js ./
# laravel-mix v6 resolves versioned file paths from filesystem root unless
# publicPath is explicitly set to a relative path
RUN sed -i "/^const mix/a mix.setPublicPath('.\/public');" webpack.mix.js

COPY resources/ ./resources/

RUN npm run prod


# Stage 2: PHP + Apache runtime
FROM php:7.4-apache

# System dependencies
RUN apt-get update && apt-get install -y \
        libzip-dev \
        libpng-dev \
        libjpeg62-turbo-dev \
        libfreetype6-dev \
        libxml2-dev \
        libicu-dev \
        libonig-dev \
        libsqlite3-dev \
        unzip \
        git \
    && docker-php-ext-configure gd \
        --with-freetype \
        --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
        pdo_mysql \
        pdo_sqlite \
        mbstring \
        bcmath \
        dom \
        zip \
        gd \
        exif \
        intl \
        opcache \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Apache: enable rewrite, set document root to public/
RUN a2enmod rewrite \
    && sed -i 's|DocumentRoot /var/www/html|DocumentRoot /var/www/html/public|' \
        /etc/apache2/sites-available/000-default.conf \
    && sed -i '/<\/VirtualHost>/i \\t<Directory /var/www/html/public>\n\t\tOptions Indexes FollowSymLinks\n\t\tAllowOverride All\n\t\tRequire all granted\n\t</Directory>' \
        /etc/apache2/sites-available/000-default.conf

WORKDIR /var/www/html

# Copy application source
COPY . .

# Set up .env with MySQL defaults (runtime env vars from docker-compose override these)
RUN cp .env.example .env

# Install PHP dependencies; skip post-install scripts (they need artisan/storage)
RUN composer install --no-dev --no-scripts --optimize-autoloader --no-interaction

# Now that vendor/ is ready, generate key and discover packages
RUN php artisan key:generate \
    && php artisan package:discover --ansi

# Publish only AdminLTE public assets (not views, which are already in the repo)
RUN php artisan vendor:publish --provider="Backpack\Base\BaseServiceProvider" --tag=adminlte --force \
    && php artisan vendor:publish --provider="Backpack\Base\BaseServiceProvider" --tag=public --force

# Remove HTTPS-only redirect from .htaccess (not needed for local HTTP dev)
RUN sed -i '/# HTTPS/{N;N;d;}' public/.htaccess

# Copy compiled frontend assets from stage 1.
# CSS is copied file-by-file (not as a directory) to avoid Docker's opaque overlay behaviour,
# which would otherwise shadow public/css/admin/icomoon/ that is a static committed asset.
COPY --from=frontend /app/public/css/app.css          ./public/css/app.css
COPY --from=frontend /app/public/css/admin/reports.css ./public/css/admin/reports.css
COPY --from=frontend /app/public/js    ./public/js
COPY --from=frontend /app/public/fonts ./public/fonts
COPY --from=frontend /app/public/sw.js ./public/sw.js
COPY --from=frontend /app/public/mix-manifest.json ./public/mix-manifest.json

# Permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

COPY docker/entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

EXPOSE 80

ENTRYPOINT ["docker-entrypoint.sh"]
CMD ["apache2-foreground"]
