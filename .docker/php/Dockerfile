FROM php:7.2.2-apache
# enable mod_rewrite
RUN a2enmod rewrite
# PHP extensions
RUN docker-php-ext-install pdo_mysql exif
# Enable xdebug for debugging & profiling
RUN pecl install xdebug && docker-php-ext-enable xdebug