# Base image PHP + Apache
FROM php:8.2-apache

# Install ekstensi PHP yang dibutuhkan
RUN docker-php-ext-install mysqli

# Salin semua file ke dalam folder web server
COPY . /var/www/html/

# Beri izin ke folder
RUN chown -R www-data:www-data /var/www/html

# Aktifkan mod_rewrite jika dibutuhkan
RUN a2enmod rewrite

# Port default Apache
EXPOSE 80

