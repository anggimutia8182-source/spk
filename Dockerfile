FROM php:8.1-apache

# Install MySQL extensions needed by CodeIgniter 3
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Enable Apache mod_rewrite for friendly URLs
RUN a2enmod rewrite

# Allow .htaccess rewrites
RUN { \
    echo '<Directory /var/www/html/>'; \
    echo '    Options -Indexes +FollowSymLinks'; \
    echo '    AllowOverride All'; \
    echo '    Require all granted'; \
    echo '</Directory>'; \
} > /etc/apache2/conf-available/codeigniter.conf \
    && a2enconf codeigniter

# Set working directory
WORKDIR /var/www/html

# Copy application files
COPY . /var/www/html/

# Permissions
RUN chown -R www-data:www-data /var/www/html

# Dynamic port binding for Railway ($PORT)
CMD ["sh", "-c", "sed -i \"s/Listen 80/Listen ${PORT:-80}/g\" /etc/apache2/ports.conf && sed -i \"s/<VirtualHost \\*:80>/<VirtualHost *:${PORT:-80}>/g\" /etc/apache2/sites-available/000-default.conf && exec apache2-foreground"]
