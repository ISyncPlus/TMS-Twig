FROM php:8.2-apache

# Install system packages required by Composer
RUN apt-get update && apt-get install -y \
    git \
    zip \
    unzip

# Enable Apache Rewrite (needed for routing)
RUN a2enmod rewrite

# Set working directory
WORKDIR /var/www/html

# Copy app files
COPY public/ .

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader

# Set correct permissions
RUN chown -R www-data:www-data /var/www/html

EXPOSE 80
