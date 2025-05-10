# Usa una imagen oficial de PHP con Composer preinstalado
FROM composer:latest AS builder

# Establecer el directorio de trabajo
WORKDIR /app

# Copiar solo los archivos esenciales primero para aprovechar la caché de Docker
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-interaction --prefer-dist

# Copiar el resto del código
COPY . .

# Instalar dependencias de Node.js y construir frontend
RUN npm install && npm run build

# Optimizar Laravel
RUN php artisan config:cache
RUN php artisan route:cache
RUN php artisan view:cache
RUN php artisan migrate --force

# Establecer permisos correctos
RUN chmod -R 775 storage bootstrap/cache

# Configurar el servidor para Laravel
CMD ["php", "-S", "0.0.0.0:8000", "-t", "public"]
