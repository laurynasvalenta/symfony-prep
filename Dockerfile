FROM php:8.4-fpm-alpine

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
