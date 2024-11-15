FROM php:8.3-apache

RUN a2enmod rewrite

RUN apt-get update
RUN apt install -y libzip-dev git wget --no-install-recommends
 
RUN docker-php-ext-install pdo mysqli pdo_mysql zip;

RUN wget https://getcomposer.org/installer -O /var/www/composer-setup.php
RUN php /var/www/composer-setup.php 
RUN mv composer.phar /usr/bin/composer 
RUN chmod +x /usr/bin/composer
 
COPY apache.conf /etc/apache2/sites-enabled/000-default.conf
 
WORKDIR /var/www