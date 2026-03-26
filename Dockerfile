FROM php:8.2-apache

# Instalar extensiones necesarias para MySQL
RUN docker-php-ext-install mysqli pdo pdo_mysql

COPY . /var/www/html/
RUN a2enmod rewrite

EXPOSE 80