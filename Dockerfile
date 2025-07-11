FROM php:8.2-apache

# Install mysqli extension
RUN docker-php-ext-install mysqli

# Enable Apache rewrite module
RUN a2enmod rewrite

# Copy backend files into Apache root
COPY backend/ /var/www/html/

# Set ownership
RUN chown -R www-data:www-data /var/www/html

EXPOSE 80