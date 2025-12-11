FROM php:8.2-apache

# FORCE CACHE INVALIDATION
ARG CACHEBUSTER=1

# Enable necessary modules
RUN a2enmod rewrite

# Install PHP extensions
RUN docker-php-ext-install mysqli pdo pdo_mysql

# FIX MPM — disable everything except prefork
RUN a2dismod mpm_event mpm_worker && \
    a2enmod mpm_prefork

# Allow .htaccess
RUN sed -i 's/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf

# Copy project
COPY . /var/www/html/
RUN chown -R www-data:www-data /var/www/html

EXPOSE 80

CMD ["apache2-foreground"]
