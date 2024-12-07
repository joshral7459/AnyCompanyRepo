FROM 841162696521.dkr.ecr.us-east-1.amazonaws.com/apachephp:latest

# Set Apache run user and group
ENV APACHE_RUN_USER=www-data
ENV APACHE_RUN_GROUP=www-data

RUN echo "Base image loaded successfully" > /var/www/html/debug.txt
# code to get Availability Zone info
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN composer require aws/aws-sdk-php
# Enable PHP module using the correct name
RUN a2enmod php7

COPY html/ /var/www/html/

WORKDIR /var/www/html

RUN ls -la /var/www/html >> /var/www/html/debug.txt

EXPOSE 80

# Restart Apache
RUN service apache2 restart

CMD ["apache2-foreground"]
