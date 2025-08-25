FROM php:8.2-apache
# enable mod_rewrite
RUN a2enmod rewrite
# install needed libraries
RUN apt-get update \
    && apt-get -y --no-install-recommends install libfontconfig1 libxrender1 libxext6 zlib1g-dev libpng-dev libfreetype6-dev libjpeg62-turbo-dev zip unzip \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* 
# PHP extensions
RUN docker-php-ext-install pdo_mysql exif gd
# Enable xdebug for debugging & profiling
RUN pecl install xdebug-3.3.1 && docker-php-ext-enable xdebug gd
# Get composer binary and install dependencies to workdir
RUN echo $PATH
RUN curl -s https://getcomposer.org/installer | php && mv composer.phar /usr/local/bin/composer
RUN pwd
RUN ls -lisah
COPY ./composer.json ./composer.lock /var/www/html
RUN php --ini
RUN composer install
