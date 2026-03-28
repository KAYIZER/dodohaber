#!/bin/bash

echo "[ENTRYPOINT] DodoHaber baslatiliyor..."

# 1. Ortam degiskenleri eksikse doldur (.env map edilmemis ihtimali!)
if [ ! -f /var/www/html/.env ]; then
    echo ".env bulunamadi. .env.example kullaniliyor."
    cp /var/www/html/.env.example /var/www/html/.env
    php artisan key:generate
fi

# 2. SQLite veritabani olusturulmamissa dosya olustur
if [ ! -f /var/www/html/database/database.sqlite ]; then
    echo "SQLite dosyasi olusturuluyor..."
    touch /var/www/html/database/database.sqlite
    # Ilk kurulumsa migration'i force ile baslat (Sadece ana tablolari kurar users vb)
    php artisan migrate --force
fi

# 3. Izinleri guncelle: Host'tan yuklenen directory permissonlari container root'unda patlamasın
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/database /var/www/html/.env

# 4. Laravel Onbellekleme: (Zorunlu, uretim ortami daha hizli calismali)
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "[ENTRYPOINT] Tüm on kosullar hazir. Supervisord baslatiliyor..."

# 5. Sistemi ayaga kaldir
exec /usr/bin/supervisord -c /etc/supervisord.conf
