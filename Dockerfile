FROM 841162696521.dkr.ecr.us-east-1.amazonaws.com/apachephp:latest

RUN echo "Base image loaded successfully" > /var/www/html/debug.txt

COPY html/ /var/www/html/
RUN echo "Files copied successfully" >> /var/www/html/debug.txt
RUN php -v

RUN echo "<?php phpinfo(); ?>" > /var/www/html/phpinfo.php
RUN echo "phpinfo.php created successfully" >> /var/www/html/debug.txt

RUN echo "<?php echo 'PHP is working!'; ?>" > /var/www/html/test.php
RUN echo "test.php created successfully" >> /var/www/html/debug.txt


WORKDIR /var/www/html

RUN ls -la /var/www/html >> /var/www/html/debug.txt

EXPOSE 80

CMD ["apache2-foreground"]
