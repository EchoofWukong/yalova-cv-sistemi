FROM php:8.2-apache

# Gerekli sistem paketlerini kur
RUN apt-get update && apt-get install -y \
    libpq-dev \
    libpng-dev \
    libzip-dev \
    zip \
    unzip \
    git \
    && docker-php-ext-install pdo pdo_pgsql gd zip

# Apache mod_rewrite aktif et (Laravel için şart)
RUN a2enmod rewrite

# Proje dosyalarını kopyala
COPY . /var/www/html

# =========================================================================
# KRİTİK ADIM: UPLOADS KLASÖRÜNÜ OLUŞTUR VE İZİN VER
# =========================================================================
RUN mkdir -p /var/www/html/public/uploads && \
    chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/public/uploads && \
    chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/public/uploads

# Çalışma dizini
WORKDIR /var/www/html

# Composer'ı yükle ve paketleri kur
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN composer install --no-dev --optimize-autoloader

# Apache'yi 80 portunda çalışacak şekilde ayarla
RUN sed -ri -e 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!/var/www/html/public!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

EXPOSE 80

# Migrations ve Apache start
CMD php artisan config:clear && php artisan view:clear && php artisan migrate --force && apache2-foreground