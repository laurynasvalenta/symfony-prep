FROM php:8.4-fpm-alpine

RUN apk add gcc libc-dev linux-headers make autoconf icu-dev \
    && pecl install xdebug \
    && docker-php-ext-install intl \
    && docker-php-ext-enable xdebug intl
RUN echo "pm.max_children=1000" >> /usr/local/etc/php-fpm.d/www.conf

COPY --chmod=0755 .docker/debug /usr/local/bin/
COPY --chown=www-data .docker/custom.ini /usr/local/etc/php/conf.d/999-custom.ini

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

