FROM php:8.2-apache

RUN apt update -y && apt upgrade -y \
    && apt install -y git unzip libzip-dev \
    && docker-php-ext-install pdo_mysql zip

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /tmp

ENV COMPOSER_ALLOW_SUPERUSER=1

RUN composer create-project symfony/skeleton:"^6.4" project --no-interaction --prefer-dist

RUN mv project/* project/.* /var/www/html/ 2>/dev/null || true

WORKDIR /var/www/html

RUN composer require symfony/apache-pack --no-interaction

RUN chown -R www-data:www-data var

RUN a2enmod rewrite

EXPOSE 80