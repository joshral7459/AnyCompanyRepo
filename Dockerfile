FROM 841162696521.dkr.ecr.us-east-1.amazonaws.com/apachephp:latest

# Install required dependencies and clean up in one layer
RUN apt-get update && apt-get install -y \
    zip \
    unzip \
    git \
    libzip-dev \
    && docker-php-ext-install zip \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Set Apache run user and group
ENV APACHE_RUN_USER=www-data \
    APACHE_RUN_GROUP=www-data

# Install Composer and AWS SDK
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
    && composer require aws/aws-sdk-php

# Enable PHP modules and Apache modules
RUN a2enmod php7 rewrite

# Copy custom Apache configuration and web content
COPY 000-default.conf /etc/apache2/sites-available/000-default.conf
COPY html/ /var/www/html/

WORKDIR /var/www/html

# Set correct permissions
RUN chown -R www-data:www-data /var/www/html

EXPOSE 80

# Set the user for the container
USER www-data

CMD ["apache2-foreground"]
