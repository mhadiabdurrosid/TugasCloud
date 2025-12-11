FROM php:8.2-apache

# FORCE CACHE INVALIDATION
ARG CACHEBUSTER=1

RUN a2enmod rewrite
RUN docker-php-ext-install mysqli pdo pdo_mysql

# HARD FIX — remove ALL MPM definitions from ALL Apache locations
RUN rm -f /etc/apache2/mods-enabled/mpm_*.load \
    /etc/apache2/mods-enabled/mpm_*.conf \
    /etc/apache2/conf-enabled/mpm_*.conf \
    /etc/apache2/conf-enabled/mpm_*.load \
    && ln -s /etc/apache2/mods-available/mpm_prefork.load /etc/apache2/mods-enabled/mpm_prefork.load \
    && ln -s /etc/apache2/mods-available/mpm_prefork.conf /etc/apache2/mods-enabled/mpm_prefork.conf

RUN sed -i 's/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf

COPY . /var/www/html/
RUN chown -R www-data:www-data /var/www/html

EXPOSE 80
CMD ["apache2-foreground"]
