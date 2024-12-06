# Use the official PHP image with Apache
FROM php:7.4-apache

# Install any PHP extensions you might need
# For example, to install mysqli:
# RUN docker-php-ext-install mysqli

# Copy the application files to the container
COPY src/ /var/www/html/

# Set the working directory
WORKDIR /var/www/html

# Apache configuration (if needed)
# For example, to enable .htaccess files:
# RUN a2enmod rewrite

# Expose port 80
EXPOSE 80

# Start Apache in the foreground
CMD ["apache2-foreground"]
