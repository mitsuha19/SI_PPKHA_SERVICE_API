# Gunakan PHP 8.3.20 ZTS dengan Alpine sebagai base image
FROM php:8.3.20-fpm-alpine3.20

# Install dependencies menggunakan apk
RUN apk update && apk add --no-cache \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    git \
    unzip \
    libzip-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd zip pdo pdo_mysql

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Set working directory
WORKDIR /var/www/html

# Salin seluruh proyek Laravel ke dalam container terlebih dahulu
COPY . /var/www/html

# Set izin untuk storage dan bootstrap/cache
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
RUN chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

RUN chown -R www-data:www-data /var/www/html/public
RUN chmod -R 755 /var/www/html/public

# Meng-expose port 8000 untuk akses ke aplikasi Laravel
EXPOSE 8001

# Menjalankan Laravel menggunakan php artisan serve
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8001"]