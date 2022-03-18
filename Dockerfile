FROM php:8-apache

RUN apt-get clean && rm -rf /var/lib/apt/lists/*

RUN apt-get update && \
    apt-get install -y libonig-dev

RUN docker-php-ext-install mysqli mbstring

COPY ./conf/vhost.conf /etc/apache2/sites-available/000-default.conf
