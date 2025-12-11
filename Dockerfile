FROM php:8.2-apache

# FORCE CACHE INVALIDATION
ARG CACHEBUSTER=1

# Enable Apache rewrite
RUN a2enmod rewrite

# PHP extensions
RUN docker-php-ext-install mysqli pdo pdo_mysql

# 🔥 FIX MPM Apache — WAJIB untuk Railway
RUN a2dismod mpm_event mpm_worker || true \
    && a2enmod mpm_prefork

# (Optional, tapi aman)
RUN rm -f /etc/apache2/mods-enabled/mpm_*.load \
    && rm -f /etc/apache2/mods-enabled/mpm_*.conf \
    && ln -s /etc/apache2/mods-available/mpm_prefork.load /etc/apache2/mods-enabled/mpm_prefork.load \
    && ln -s /etc/apache2/mods-available/mpm_prefork.conf /etc/apache2/mods-enabled/mpm_prefork.conf

# Allow .htaccess (meski kamu gak pakai)
RUN sed -i 's/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf

# Copy app
COPY . /var/www/html/
RUN chown -R www-data:www-data /var/www/html

EXPOSE 80

CMD ["apache2-foreground"]
