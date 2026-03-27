FROM php:8.2-apache

# Gerekli sistem paketlerini kur
RUN apt-get update && apt-get install -y \
    libpq-dev \
    libpng-dev \
    zip \
    unzip \
    git \
    && docker-php-ext-install pdo pdo_pgsql gd

# Apache mod_rewrite aktif et (Laravel için şart)
RUN a2enmod rewrite

# Proje dosyalarını kopyala
COPY . /var/www/html

# Klasör izinlerini ayarla
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Çalışma dizini
WORKDIR /var/www/html

# Composer'ı yükle ve paketleri kur
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN composer install --no-dev --optimize-autoloader

# Apache'yi 80 portunda çalışacak şekilde ayarla (Render 10000 portunu sever ama Docker'da 80 standarttır)
RUN sed -ri -e 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!/var/www/html/public!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

EXPOSE 80

CMD php artisan migrate --force && apache2-foreground