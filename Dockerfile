FROM php:8.1.0

ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/
RUN chmod +x /usr/local/bin/install-php-extensions

RUN install-php-extensions bcmath

COPY --from=composer:2.2 /usr/bin/composer /usr/local/bin/composer