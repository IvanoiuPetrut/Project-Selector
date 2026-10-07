FROM php:8.3-apache

RUN docker-php-ext-install pdo_mysql && a2enmod headers

COPY docker/php.ini /usr/local/etc/php/conf.d/app.ini
COPY docker/apache.conf /etc/apache2/conf-enabled/zz-app.conf
# The code stays owned by root, so the web server user cannot change it
COPY . /var/www/html/
RUN chown root:root /var/www/html && chmod 755 /var/www/html

EXPOSE 80
