FROM ubuntu:22.04

ARG DEBIAN_FRONTEND=noninteractive

# hadolint ignore=DL3008
RUN apt-get update \
    && apt-get install -y --no-install-recommends \
        apache2 \
        default-mysql-client \
        php8.1 \
        php8.1-mysql \
    && rm -rf /var/lib/apt/lists/*

RUN rm -r /var/www/html/*
COPY ./qbee /var/www/html
COPY ./.env /var/www/html/.env
COPY ./assets/apache2/000-default.conf /etc/apache2/sites-available/000-default.conf
RUN chown -R www-data:www-data /var/www/html/ \
    && a2enmod rewrite

COPY ./assets /app
RUN chmod +x -R /app/
