FROM php:apache

# Installs needed extensions to use pdo_connect and mysqli to connect to phpmyadmin
RUN docker-php-ext-install pdo pdo_mysql mysqli

# Copy custom php settings to deal with much larger file sizes
COPY custom.ini /usr/local/etc/php/conf.d/
