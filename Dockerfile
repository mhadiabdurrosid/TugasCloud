FROM php:8.2-apache

# Aktifkan Apache Rewrite
RUN a2enmod rewrite

# Izinkan .htaccess
RUN sed -i 's/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf

# Copy semua file ke DocumentRoot
COPY . /var/www/html/

# Install ekstensi php penting
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Izin folder
RUN chown -R www-data:www-data /var/www/html

# Expose port 80 (WAJIB)
EXPOSE 80

# Jalankan Apache
CMD ["apache2-foreground"]
