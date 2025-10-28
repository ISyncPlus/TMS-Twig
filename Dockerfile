FROM php:8.2-apache

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Enable Apache Rewrite
RUN a2enmod rewrite

# Copy app to Apache root
COPY . /var/www/html

WORKDIR /var/www/html

# Install dependencies
RUN composer install --no-dev --optimize-autoloader

EXPOSE 80
