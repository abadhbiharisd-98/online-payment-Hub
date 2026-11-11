FROM php:8.1-apache

# Install MariaDB server & client, and PHP GD dependencies
RUN apt-get update && apt-get install -y \
    mariadb-server \
    mariadb-client \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd mysqli pdo pdo_mysql \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Point Apache DocumentRoot directly to the spms folder
ENV APACHE_DOCUMENT_ROOT /var/www/html/spms
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf \
    && sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Configure Apache to not leak internal ports in redirects
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf \
    && echo "UseCanonicalName Off" >> /etc/apache2/apache2.conf \
    && echo "UseCanonicalPhysicalPort Off" >> /etc/apache2/apache2.conf

# Enable Apache rewrite module
RUN a2enmod rewrite

# Copy all application files to Apache root
COPY . /var/www/html/

# Make upload directory writable
RUN mkdir -p /var/www/html/spms/upload/users && chmod -R 777 /var/www/html/spms/upload

# Setup entrypoint script
COPY docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

# Expose HTTP port
EXPOSE 80

ENTRYPOINT ["docker-entrypoint.sh"]
