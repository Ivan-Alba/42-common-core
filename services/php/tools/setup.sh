#!/bin/sh

# Make export of secrets
SECRET_FILE="/run/secrets/db_password"

echo "Checking database credentials..."

if [ -f "$SECRET_FILE" ]; then
    export DB_PASSWORD=$(cat "$SECRET_FILE" | tr -d '\r\n')
    echo "Database password loaded from secrets."
else
    # Si no existe el archivo y tampoco hay una variable previa (fail-fast)
    if [ -z "$DB_PASSWORD" ]; then
        echo "FATAL ERROR: No database password found!"
        echo "The secret file '$SECRET_FILE' is missing and DB_PASSWORD is not set."
        exit 1
    fi
    echo "Warning: Secret file not found, using environment variable."
fi

# Instalar dependencias
# (Solo si no están ya en el volumen, para acelerar el arranque)
composer install --no-interaction --no-scripts

if [ "$RUN_SETUP" = "true" ]; then
    # Gestionar el enlace simbólico de Storage (Limpieza y Recreación Forzada)
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

    # Ejecutar migraciones
    echo "Running migrations..."
    php artisan migrate --seed --force

    # Aplicar permisos al VOLUMEN compartido (se queda guardado en el disco)
    chown -R www-data:www-data /var/www/storage
    chmod -R 775 /var/www/storage
fi

chown -R www-data:www-data /var/www/bootstrap/cache /var/www/public

# Aplicamos permisos de lectura/escritura/ejecución (775)
chmod -R 775 /var/www/bootstrap/cache

# Ejecutar el comando principal (php-fpm o el definido en el Dockerfile)
echo "Laravel setup completed. Starting application..."
exec "$@"