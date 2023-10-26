FROM php:apache

RUN apt-get update && apt-get install -y sendmail

RUN docker-php-ext-install pdo pdo_mysql mysqli
