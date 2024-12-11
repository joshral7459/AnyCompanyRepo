FROM 841162696521.dkr.ecr.us-east-1.amazonaws.com/apachephp:latest

# Install required dependencies and clean up in one layer
RUN apt-get update && apt-get install -y \
    zip \
    unzip \
    git \
    libzip-dev \
    nano \
    ssl-cert \
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
RUN a2enmod php7 rewrite ssl

# Generate self-signed certificate
RUN openssl req -x509 -nodes -days 365 -newkey rsa:2048 \
    -keyout /etc/ssl/private/apache-selfsigned.key \
    -out /etc/ssl/certs/apache-selfsigned.crt \
    -subj "/C=US/ST=State/L=City/O=Organization/OU=OrganizationalUnit/CN=localhost"

# Copy custom Apache configuration and web content
COPY 000-default.conf /etc/apache2/sites-available/000-default.conf
COPY html/ /var/www/html/

# Create and copy SSL configuration
RUN echo "<VirtualHost *:443>\n\
    ServerName localhost\n\
    DocumentRoot /var/www/html\n\
    SSLEngine on\n\
    SSLCertificateFile /etc/ssl/certs/apache-selfsigned.crt\n\
    SSLCertificateKeyFile /etc/ssl/private/apache-selfsigned.key\n\
</VirtualHost>" > /etc/apache2/sites-available/default-ssl.conf

RUN a2ensite default-ssl

WORKDIR /var/www/html

# Set correct permissions
RUN chown -R www-data:www-data /var/www/html && \
    chmod -R 755 /var/www/html

EXPOSE 80 443

# Switch to www-data user
RUN sed -i 's/User ${APACHE_RUN_USER}/User www-data/' /etc/apache2/apache2.conf
RUN sed -i 's/Group ${APACHE_RUN_GROUP}/Group www-data/' /etc/apache2/apache2.conf

# Modify Apache to also listen on 443
RUN echo "Listen 443" >> /etc/apache2/ports.conf

CMD ["apache2-foreground"]
