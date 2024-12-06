# Use the official PHP image with Apache from ECR
FROM 841162696521.dkr.ecr.us-east-1.amazonaws.com/apachephp:latest

# Install any PHP extensions you might need
# For example, to install mysqli:
# RUN docker-php-ext-install mysqli

# Copy the application files to the container
COPY html/ /var/www/html/

RUN echo "<?php phpinfo(); ?>" > /var/www/html/phpinfo.php


# Set the working directory
WORKDIR /var/www/html

# Apache configuration (if needed)
# For example, to enable .htaccess files:
# RUN a2enmod rewrite

# Expose port 80
EXPOSE 80

# Start Apache in the foreground
CMD ["apache2-foreground"]
