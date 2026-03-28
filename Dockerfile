FROM php:8.2-fpm-alpine

# Sistemsel paketler ve eklentiler (Nginx, Supervisor, SQLite vb.)
RUN apk add --no-cache \
    nginx \
    supervisor \
    curl \
    git \
    zip \
    unzip \
    sqlite-dev \
    nodejs \
    npm \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    libxml2-dev \
    oniguruma-dev \
    icu-dev \
    libzip-dev \
    bash

# PHP eklentilerini kur
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo_sqlite mbstring exif pcntl bcmath gd xml intl zip

# Composer'ı global olarak kopyala
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Çalışma dizinini seç
WORKDIR /var/www/html

# Proje dosyalarını kopyala
COPY . .

# Nginx ayar dosyasını kopyala (önce eski default config'i siliyoruz)
RUN rm /etc/nginx/http.d/default.conf
COPY docker/nginx.conf /etc/nginx/http.d/default.conf

# Nginx'in www-data kullanıcısıyla çalışması için güncelliyoruz
RUN sed -i 's/user nginx;/user www-data;/g' /etc/nginx/nginx.conf

# Git sahiplik uyarısını çöz (Composer için)
RUN git config --global --add safe.directory /var/www/html

# Bağımlılıkları kur ve derlemeyi yap
RUN composer install --optimize-autoloader --no-dev
RUN npm install
RUN npm run build

# Docker Config ve Script'leri içeriye al
COPY docker/supervisord.conf /etc/supervisord.conf
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh

# Dosya izinlerini ayarla
RUN chmod +x /usr/local/bin/entrypoint.sh
RUN chown -R www-data:www-data /var/www/html

# 80 portunu dışa aç
EXPOSE 80

# Konteyner ayağa kalkarken entrypoint komutunu çalıştır (Supervisor'ı tetikler)
ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
