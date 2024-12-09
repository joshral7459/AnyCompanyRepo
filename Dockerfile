FROM 841162696521.dkr.ecr.us-east-1.amazonaws.com/apachephp:latest

# Install required dependencies
RUN apt-get update && apt-get install -y \
    zip \
    unzip \
    git \
    libzip-dev \
    && docker-php-ext-install zip

# Set Apache run user and group
ENV APACHE_RUN_USER=www-data
ENV APACHE_RUN_GROUP=www-data

RUN echo "Base image loaded successfully" > /var/www/html/debug.txt
# code to get Availability Zone info
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
# Install AWS SDK
RUN composer require aws/aws-sdk-php
# Enable PHP module using the correct name
RUN a2enmod php7 rewrite

# Copy custom Apache configuration
COPY custom-apache-config.conf /etc/apache2/sites-available/000-default.conf

COPY html/ /var/www/html/

WORKDIR /var/www/html

EXPOSE 80

CMD ["apache2-foreground"]
