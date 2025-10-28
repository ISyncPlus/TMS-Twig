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

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Copy composer files first for dependency installation
COPY composer.json composer.lock* ./

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader

# Copy app files (preserve `public/` directory so runtime can serve it)
COPY public/ public/
COPY templates/ ./templates/
COPY bin/ ./bin/

# Set correct permissions
RUN chown -R www-data:www-data /var/www/html

EXPOSE 80

# Start Apache in the foreground (php:*-apache provides apache2-foreground)
CMD ["apache2-foreground"]
