FROM php:8.2-apache

# 🚀 PAKSA REBUILD FULL (ubah angka kalau perlu)
ARG CACHEBUSTER=1005

# Enable Rewrite
RUN a2enmod rewrite

# Install PHP Extensions
RUN docker-php-ext-install mysqli pdo pdo_mysql

# 🚨 FIX PENTING → HAPUS MPM LAIN & PAKSA PREFORK
RUN a2dismod mpm_event mpm_worker || true \
    && a2enmod mpm_prefork

# Hilangkan file MPM lain (anti "More than one MPM loaded")
RUN rm -f /etc/apache2/mods-enabled/mpm_*.load \
    && rm -f /etc/apache2/mods-enabled/mpm_*.conf \
    && ln -s /etc/apache2/mods-available/mpm_prefork.load /etc/apache2/mods-enabled/mpm_prefork.load \
    && ln -s /etc/apache2/mods-available/mpm_prefork.conf /etc/apache2/mods-enabled/mpm_prefork.conf

# 🔥 Railway pakai random PORT, jadi patch Apache
RUN sed -i "s/Listen 80/Listen \$PORT/g" /etc/apache2/ports.conf \
    && sed -i "s/:80/:\$PORT/g" /etc/apache2/sites-enabled/000-default.conf

# Allow .htaccess
RUN sed -i 's/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf

# Copy project
COPY . /var/www/html/
RUN chown -R www-data:www-data /var/www/html

EXPOSE 80

CMD ["apache2-foreground"]
