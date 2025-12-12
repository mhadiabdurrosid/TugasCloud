FROM php:8.2-apache

# Install PHP extensions
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Enable rewrite
RUN a2enmod rewrite

# Allow .htaccess
RUN sed -i 's/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf

# Set PORT for Railway
ENV PORT=8080
RUN sed -i "s/Listen 80/Listen 8080/" /etc/apache2/ports.conf
RUN sed -i "s/:80/:8080/" /etc/apache2/sites-enabled/000-default.conf

# Copy app
COPY . /var/www/html/

WORKDIR /var/www/html

# Fix permissions
RUN mkdir -p /var/www/html/uploads \
    && chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html/uploads
