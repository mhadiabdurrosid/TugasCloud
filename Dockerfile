FROM php:8.2-apache

# Aktifkan Apache Rewrite
RUN a2enmod rewrite

# Fix MPM conflict
RUN a2dismod mpm_event || true
RUN a2dismod mpm_worker || true
RUN a2enmod mpm_prefork

# Izinkan .htaccess
RUN sed -i 's/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf

# Copy semua file ke DocumentRoot
COPY . /var/www/html/

# Install ekstensi php penting
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Izin folder
RUN chown -R www-data:www-data /var/www/html

EXPOSE 80

CMD ["apache2-foreground"]
