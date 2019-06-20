FROM php:7.2.2-apache
# enable mod_rewrite
RUN a2enmod rewrite
# PHP extensions
RUN docker-php-ext-install pdo_mysql exif