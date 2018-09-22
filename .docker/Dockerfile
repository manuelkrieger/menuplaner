FROM php:7.0-apache
#FROM php:7.1.8-apache

MAINTAINER Manuel Krieger

COPY . /srv/app
COPY .docker/vhost.conf /etc/apache2/sites-available/000-default.conf

WORKDIR /srv/app

RUN a2enmod rewrite

RUN apt-get update
#RUN apt-get install -y libmagickwand-dev --no-install-recommends && pecl install imagick && docker-php-ext-enable imagick
#RUN apt-get install -y libmcrypt-dev --no-install-recommends && docker-php-ext-install mcrypt

RUN docker-php-ext-install mbstring pdo pdo_mysql && chown -R www-data:www-data /srv/app

# Enable and configure xdebug
RUN pecl install xdebug
RUN docker-php-ext-enable xdebug
RUN sed -i '1 a xdebug.remote_autostart=true' /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
RUN sed -i '1 a xdebug.remote_mode=req' /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
RUN sed -i '1 a xdebug.remote_handler=dbgp' /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
RUN sed -i '1 a xdebug.remote_connect_back=1' /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
RUN sed -i '1 a xdebug.remote_port=9000' /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
RUN sed -i '1 a xdebug.remote_enable=1' /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
