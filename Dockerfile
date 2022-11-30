FROM php:7.4.25-apache AS base
WORKDIR /var/www/html

# install needed libraries
RUN apt-get update \
  && apt-get -y --no-install-recommends install libfontconfig1 libxrender1 libxext6 zlib1g-dev libpng-dev libfreetype6-dev libjpeg62-turbo-dev \
  && apt-get clean \
  && rm -rf /var/lib/apt/lists/* 

RUN a2enmod rewrite

RUN docker-php-ext-install \ 
  gd \
  pdo_mysql \
  exif

# op cache
RUN docker-php-ext-install -j "$(nproc)" opcache 

RUN set -ex; \
  { \
  echo "; Cloud Run enforces memory & timeouts"; \
  echo "memory_limit = -1"; \
  echo "max_execution_time = 0"; \
  echo "; File upload at Cloud Run network limit"; \
  echo "upload_max_filesize = 32M"; \
  echo "post_max_size = 32M"; \
  echo "; Configure Opcache for Containers"; \
  echo "opcache.enable = On"; \
  echo "opcache.validate_timestamps = Off"; \
  echo "; Configure Opcache Memory (Application-specific)"; \
  echo "opcache.memory_consumption = 32"; \
  } > "$PHP_INI_DIR/conf.d/cloud-run.ini"

RUN pecl install opencensus-alpha

# Use the PORT environment variable in Apache configuration files.
# https://cloud.google.com/run/docs/reference/container-contract#port
RUN sed -i 's/80/${PORT}/g' /etc/apache2/sites-available/000-default.conf /etc/apache2/ports.conf

FROM base AS debug

# RUN enable-xdebug in PHP 8 env
RUN pecl install xdebug && docker-php-ext-enable xdebug
# Configure PHP for development.
# https://github.com/docker-library/docs/blob/master/php/README.md#configuration
RUN mv "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini"

FROM composer:2.1.11 AS build

WORKDIR /var/www/html
# ensures we use docker cache and only rebuild composer
# if the dependencies have changed
COPY composer.json .
COPY composer.lock .
RUN composer install --no-dev --no-scripts --ignore-platform-reqs
COPY . .
RUN composer dump-autoload --optimize
RUN ls
RUN php build/build.php
RUN rm -r build/

FROM base AS final

# Configure PHP for production.
# https://github.com/docker-library/docs/blob/master/php/README.md#configuration
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

COPY --from=build /var/www/html /var/www/html



# docker pull myimage:latest-build | true
# docker pull myimage:latest | true

# docker build . --target=build --cache-from=myimage:latest-build -t myimage:latest-build
# docker build . --cache-from=myimage:latest-build --cache-from=myimage:latest -t myimage:latest

# docker push myimage:latest-build
# docker push myimage:latest


# use docker build from circleci and then push to gcloud run
# gcloud auth configure-docker
# docker tag dropapp europe-west1-docker.pkg.dev/dropapp-242214/cloudrun/dropapp
# docker push europe-west1-docker.pkg.dev/dropapp-242214/cloudrun/dropapp
# gcloud run deploy

# gcloud config set builds/use_kaniko True
# docker build -t dropapp . && docker run -e PORT=8080 -p 81:8080 dropapp
# use cloud build as (a) we only have 1gb free data transfer from circleci
# and (b) cloud build offers 120 mins free per day

# gcloud run deploy dropapp-test --source . --project dropapp-242214 --add-cloudsql-instances=dropapp-242214:europe-west1:boxtribute-production

# docker run -it dropapp sh