FROM php:7.4.25-apache
# enable mod_rewrite
RUN a2enmod rewrite
# install needed libraries
RUN apt-get update \
    && apt-get -y --no-install-recommends install libfontconfig1 libxrender1 libxext6 zlib1g-dev libpng-dev libfreetype6-dev libjpeg62-turbo-dev \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* 
# PHP extensions
RUN docker-php-ext-install pdo_mysql exif gd
# Enable xdebug for debugging & profiling
RUN pecl install xdebug-3.1.5 && docker-php-ext-enable xdebug gd