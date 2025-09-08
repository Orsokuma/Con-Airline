FROM php:8.0-apache

COPY ./docker/ssl/configure_ssl.sh /usr/local/bin/configure_ssl.sh
RUN chmod +x /usr/local/bin/configure_ssl.sh

COPY ./docker/ssl /docker/ssl

ARG SSL_ENABLED

RUN /usr/local/bin/configure_ssl.sh

EXPOSE 80
EXPOSE 443

RUN apt-get update
RUN apt-get upgrade -y
RUN apt-get install -y libpng-dev cron curl libcurl4-openssl-dev libpq-dev zlib1g-dev libsodium-dev libxslt-dev
RUN docker-php-ext-install mysqli gd pdo pdo_mysql sockets exif curl sodium xsl intl


# Use dev php.ini
# RUN cp /usr/src/php/php.ini-development /usr/src/php/php.ini

COPY . /var/www/html
