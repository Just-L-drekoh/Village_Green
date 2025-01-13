FROM php:8.3-apache

RUN a2enmod rewrite

RUN apt-get update && \
    apt-get install -y \
    libzip-dev \
    libicu-dev \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    git \
    wget \
    curl \
    --no-install-recommends && \
    rm -rf /var/lib/apt/lists/*

# Installer les extensions PHP nécessaires
RUN docker-php-ext-install pdo pdo_mysql mysqli zip intl opcache

# Install OPCache
RUN docker-php-ext-install opcache

# installation de xDebug via pecl
# pecl est un dépôt pour les extensions PHP, plus d'informations ici : https://pecl.php.net/
RUN pecl install xdebug
# activation de xDebug
RUN docker-php-ext-enable xdebug
# Fichier de configuration : /usr/local/etc/php
RUN echo "xdebug.mode=debug" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
RUN echo "xdebug.start_with_request=yes" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
RUN echo "xdebug.client_host=host.docker.internal" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

# Add an OPCache configuration file
COPY opcache.ini /usr/local/etc/php/conf.d/opcache.ini

# Install nvm
RUN curl -o- https://raw.githubusercontent.com/nvm-sh/nvm/v0.40.0/install.sh | bash \
    && export NVM_DIR="/root/.nvm" \
    && [ -s "$NVM_DIR/nvm.sh" ] && \. "$NVM_DIR/nvm.sh" \
    && nvm install 22
    
RUN wget https://getcomposer.org/installer -O /var/www/composer-setup.php
RUN php /var/www/composer-setup.php 
RUN mv composer.phar /usr/bin/composer 
RUN chmod +x /usr/bin/composer
 
COPY apache.conf /etc/apache2/sites-enabled/000-default.conf


 
WORKDIR /var/www

# Attribuer tous les droits à /var/www
RUN chmod -R 777 /var/www

# Assurer que l'utilisateur www-data possède tous les fichiers
RUN chown -R www-data:www-data /var/www