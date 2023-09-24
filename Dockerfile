FROM php:8.2.10-apache-bookworm as site

RUN apt-get update \
    && apt-get install -y --no-install-recommends \
        default-mysql-client=1.1.0 \
    && rm -rf /var/lib/apt/lists/*

RUN rm /etc/apache2/sites-enabled/* \
    && ln -s /etc/apache2/sites-available/qbee.conf /etc/apache2/sites-enabled/qbee.conf \
    && docker-php-ext-install mysqli \
    && a2enmod rewrite

COPY ./assets/wait-for-mysql.sh /tmp/wait-for-mysql.sh
RUN chmod +x /tmp/wait-for-mysql.sh


FROM mysql:8.1.0 as db

COPY ./assets/db/schema.sql /docker-entrypoint-initdb.d/schema.sql
