# Use official PHP Apache image
FROM php:8.2-apache

# Copy files FROM the backend/ folder INTO Apache web root
COPY backend/ /var/www/html/

# Enable URL rewriting
RUN a2enmod rewrite

# Fix permissions (optional but good)
RUN chown -R www-data:www-data /var/www/html

EXPOSE 80