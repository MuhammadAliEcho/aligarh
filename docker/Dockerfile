FROM php:8.0-apache

# Install system dependencies and PHP extensions
RUN apt-get update && \
    apt-get install -y \
        libpng-dev \
        libjpeg-dev \
        libfreetype6-dev \
        libzip-dev \
        zip \
        unzip \
        curl \
        gnupg \
        ca-certificates \
        git \
        nano && \
    docker-php-ext-configure gd --with-freetype --with-jpeg && \
    docker-php-ext-install pdo pdo_mysql gd zip && \
    a2enmod rewrite

# Copy custom Apache virtual host configuration
COPY docker/vhost.conf /etc/apache2/sites-available/000-default.conf

# Install Composer globally
RUN curl -sS https://getcomposer.org/installer | php && \
    mv composer.phar /usr/local/bin/composer && \
    chmod +x /usr/local/bin/composer

EXPOSE 80
