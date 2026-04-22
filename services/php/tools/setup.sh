#!/bin/sh

# 1. Instalar dependencias
# (Solo si no están ya en el volumen, para acelerar el arranque)
composer install --no-interaction --no-scripts

# 2. Gestionar el enlace simbólico de Storage (Limpieza y Recreación Forzada)
echo "Ensuring fresh storage link..."

# Eliminamos cualquier rastro previo de forma agresiva. 
# -f evita errores si no existe, 2>/dev/null silencia advertencias irrelevantes.
rm -f /var/www/public/storage 2>/dev/null
rm -rf /var/www/public/storage 2>/dev/null

# Intentamos crear el link mediante Artisan (el método oficial)
php artisan storage:link --force

# Red de seguridad: Si por un conflicto de volumen Artisan no pudo crear el link, 
# lo creamos manualmente usando el comando nativo de Linux 'ln -s'.
if [ ! -L /var/www/public/storage ]; then
    echo "Artisan link failed, creating manual symbolic link..."
    ln -s /var/www/storage/app/public /var/www/public/storage
fi

# 3. Ejecutar migraciones
echo "Running migrations..."
php artisan migrate --seed --force

# 4. Asegurar permisos (Fundamental para que Unity pueda leer los Avatares)
echo "Setting permissions..."

# Primero cambiamos el dueño de toda la estructura de storage y la carpeta public
chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache /var/www/public

# Aplicamos permisos de lectura/escritura/ejecución (775)
# El bit de 'ejecución' en carpetas es lo que permite al servidor "entrar" en ellas
chmod -R 775 /var/www/storage /var/www/bootstrap/cache

# 5. Ejecutar el comando principal (php-fpm o el definido en el Dockerfile)
echo "Laravel setup completed. Starting application..."
exec "$@"