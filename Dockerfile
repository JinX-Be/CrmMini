# syntax=docker/dockerfile:1

FROM php:8.3-apache

# --- System dependencies & PHP extensions -----------------------------------
RUN apt-get update && apt-get install -y --no-install-recommends \
        libpq-dev \
        libzip-dev \
        libonig-dev \
        unzip \
        git \
    && docker-php-ext-install pdo pdo_pgsql pgsql mbstring zip bcmath \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# --- Composer ----------------------------------------------------------------
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# --- Apache: serve from public/ and allow .htaccess rewrites -----------------
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN a2enmod rewrite \
    && sed -ri 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf \
    && sed -ri 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf \
    && sed -ri 's!AllowOverride None!AllowOverride All!g' /etc/apache2/apache2.conf

WORKDIR /var/www/html

# --- PHP dependencies (cached layer) -----------------------------------------
COPY composer.json composer.lock* ./
RUN composer install --no-dev --prefer-dist --no-interaction --no-progress --no-scripts --no-autoloader

# --- Application code ---------------------------------------------------------
COPY . .
RUN composer dump-autoload --optimize --no-dev --no-scripts \
    && chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R ug+rw storage bootstrap/cache

# --- Startup ------------------------------------------------------------------
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

# Render provides $PORT (default 10000); entrypoint binds Apache to it.
EXPOSE 10000

ENTRYPOINT ["entrypoint.sh"]
