FROM php:8.2-apache 
COPY . /var/www/html
RUN apt update -y && apt upgrade -y \
&& docker-php-ext-install pdo_mysql
expose 80
