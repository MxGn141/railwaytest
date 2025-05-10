FROM php:8.1-fpm
WORKDIR /app

COPY . .
RUN composer install && npm install && php artisan migrate --force

CMD ["php", "-S", "0.0.0.0:8000", "-t", "public"]
