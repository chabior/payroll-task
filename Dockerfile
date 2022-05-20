FROM php:8.1.0

ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/
RUN chmod +x /usr/local/bin/install-php-extensions

RUN install-php-extensions bcmath pgsql pdo_pgsql pcov

RUN apt-get update && apt-get install -y libpq-dev libmcrypt-dev libonig-dev libzip-dev  \
    libmagickwand-dev --no-install-recommends

RUN apt-get install -y --no-install-recommends git zip unzip

COPY --from=composer:2.2 /usr/bin/composer /usr/local/bin/composer