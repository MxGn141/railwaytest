# Usa una imagen oficial de PHP con Composer preinstalado
FROM php:8.1-fpm

# Instala Composer manualmente en caso de que no venga preinstalado
RUN apt-get update && apt-get install -y curl unzip \
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Establece el directorio de trabajo
WORKDIR /app

# Copia los archivos esenciales primero para aprovechar la caché de Docker
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-interaction --prefer-dist
RUN composer self-update
RUN composer clear-cache

# Copia el resto del código
COPY . .

# Instala dependencias de Node.js y construye el frontend
RUN npm cache clean --force
RUN npm install
RUN npm run build

# Optimiza Laravel para producción
RUN php artisan config:cache
RUN php artisan route:cache
RUN php artisan view:cache

# Establecer permisos correctos para almacenamiento y caché
RUN chmod -R 775 storage bootstrap/cache

# Ejecuta migraciones de base de datos
RUN php artisan migrate --force || true  # Usa `|| true` para evitar que falle si la BD aún no está lista

# Configura el servidor integrado de PHP
CMD ["php", "-S", "0.0.0.0:8000", "-t", "public"]
