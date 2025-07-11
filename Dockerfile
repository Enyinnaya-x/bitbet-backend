# Use official PHP 8.2 with Apache
FROM php:8.2-apache

# Enable Apache mod_rewrite (good for future-proofing)
RUN a2enmod rewrite

# Optional: enable useful PHP extensions
RUN docker-php-ext-install mysqli

# Copy your code to the container's web root
COPY . /var/www/html/

# Set correct working directory
WORKDIR /var/www/html/

# Optional: set file permissions (Apache user owns it)
RUN chown -R www-data:www-data /var/www/html

# Expose port 80 (default Apache port)
EXPOSE 80