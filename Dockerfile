FROM node:20 AS node_bin
FROM php:8.4.14-apache

COPY --from=node_bin /usr/local/lib/node_modules /usr/local/lib/node_modules
COPY --from=node_bin /usr/local/bin/node /usr/local/bin/node
RUN ln -s /usr/local/lib/node_modules/npm/bin/npm-cli.js /usr/local/bin/npm

#Install system dependencies and PHP extensions
RUN apt-get update && apt-get install -y \
    git curl zip unzip libpng-dev libjpeg-dev libfreetype6-dev libxml2-dev libzip-dev libonig-dev ca-certificates \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip \
    && a2enmod rewrite ssl

WORKDIR /var/www/html

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
COPY . .

RUN composer install --no-dev --optimize-autoloader

#Copy the rest of the application


#Set Apache document root to Laravel's "public"
ENV APACHE_DOCUMENT_ROOT /var/www/html/public

RUN sed -ri -e 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/*.conf

#SSL + redirect configuration
RUN mkdir -p /etc/apache2/ssl \
    && openssl req -x509 -nodes -days 365 -newkey rsa:2048 \
       -keyout /etc/apache2/ssl/server.key \
       -out /etc/apache2/ssl/server.crt \
       -subj '/C=BR/ST=SP/L=SaoPaulo/O=Localhost/OU=Dev/CN=localhost' \
    # HTTPS VirtualHost
    && echo '<IfModule mod_ssl.c>\n\
<VirtualHost *:443>\n\
    ServerName localhost\n\
    DocumentRoot /var/www/html/public\n\
    SSLEngine on\n\
    SSLCertificateFile /etc/apache2/ssl/server.crt\n\
    SSLCertificateKeyFile /etc/apache2/ssl/server.key\n\
    <Directory /var/www/html/public>\n\
        AllowOverride All\n\
        Require all granted\n\
    </Directory>\n\
</VirtualHost>\n\
</IfModule>' > /etc/apache2/sites-available/default-ssl.conf \
    && a2ensite default-ssl.conf \
    # HTTP → HTTPS redirect VirtualHost
    && echo '<VirtualHost *:80>\n\
    ServerName localhost\n\
    RewriteEngine On\n\
    RewriteCond %{HTTPS} off\n\
    RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]\n\
</VirtualHost>' > /etc/apache2/sites-available/redirect.conf \
    && a2ensite redirect.conf \
    
    # Disable default site so the redirect takes priority
    && a2dissite 000-default.conf

#Permissions
RUN chown -R www-data:www-data storage bootstrap/cache \
 && chmod -R 775 storage bootstrap/cache


#Expose both ports (HTTP and HTTPS)
EXPOSE 80 443

#Start Apache
CMD ["apache2-foreground"]

