# FROM richarvey/nginx-php-fpm:latest

# COPY . .

# # Image config
# ENV SKIP_COMPOSER 1
# ENV WEBROOT /var/www/html/public
# ENV PHP_ERRORS_STDERR 1
# ENV RUN_SCRIPTS 1
# ENV REAL_IP_HEADER 1

# # Laravel config
# ENV APP_ENV production
# ENV APP_DEBUG false
# ENV LOG_CHANNEL stderr

# # Allow composer to run as root
# ENV COMPOSER_ALLOW_SUPERUSER 1

# CMD ["/start.sh"]

# Use an official PHP runtime as a parent image
FROM php:7.4-apache

# Set the working directory in the container
WORKDIR /var/www/html

# Install system dependencies
RUN apt-get update && \
 apt-get install -y \
 git \
 curl \
 libpng-dev \
 libonig-dev \
 libxml2-dev \
 zip \
 unzip \
 nodejs \
 npm

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Copy composer.json and composer.lock to take advantage of Docker cache
COPY composer.json composer.lock ./

# Install application dependencies
RUN composer install --no-dev --optimize-autoloader

# Copy the application files to the container
COPY . .

# Set up Laravel environment
RUN cp .env.example .env && \
 php artisan key:generate && \
 php artisan config:cache && \
 php artisan route:cache

# Install Node.js dependencies
RUN npm install

# Build frontend assets (adjust the command according to your Laravel project)
RUN npm run production

# Expose port 80 and start Apache
EXPOSE 80
CMD ["apache2-foreground"]

