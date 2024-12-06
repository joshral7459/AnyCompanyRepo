FROM 841162696521.dkr.ecr.us-east-1.amazonaws.com/apachephp:latest

RUN echo "Base image loaded successfully" > /var/www/html/debug.txt

# Check PHP version
RUN php -v

# List available PHP modules
RUN ls /etc/apache2/mods-available | grep php

# Enable PHP module using the correct name
RUN a2enmod php7

# Verify Apache PHP module
RUN apache2ctl -M | grep php

# Ensure proper PHP file handling
RUN echo "AddType application/x-httpd-php .php" >> /etc/apache2/apache2.conf

COPY html/ /var/www/html/
RUN echo "Files copied successfully" >> /var/www/html/debug.txt

RUN echo "<?php phpinfo(); ?>" > /var/www/html/phpinfo.php
RUN echo "phpinfo.php created successfully" >> /var/www/html/debug.txt

RUN echo "<?php echo 'PHP is working!'; ?>" > /var/www/html/test.php
RUN echo "test.php created successfully" >> /var/www/html/debug.txt

WORKDIR /var/www/html

RUN ls -la /var/www/html >> /var/www/html/debug.txt

EXPOSE 80

# Restart Apache
RUN service apache2 restart

CMD ["apache2-foreground"]
