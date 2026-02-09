# syntax=docker/dockerfile:1
FROM webdevops/php-apache:8.3

WORKDIR /app
ENV WEB_DOCUMENT_ROOT=/app/public

# Install Composer (image already includes many PHP extensions)
COPY --from=composer:2 /usr/bin/composer /usr/local/bin/composer

# Copy the application code
COPY . .

# Install PHP dependencies (now that artisan exists for scripts)
RUN composer install --optimize-autoloader --no-interaction

# Ensure Laravel can write to storage and cache directories
RUN chown -R application:application storage bootstrap/cache

USER application

EXPOSE 80
