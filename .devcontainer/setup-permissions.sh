#!/bin/bash

echo "🚀 Configurando permisos de Laravel..."

# Crear directorios necesarios
echo "📁 Creando directorios..."
mkdir -p /var/www/html/storage/{logs,framework/{cache,sessions,views},app/public}
mkdir -p /var/www/html/bootstrap/cache

# Establecer propietario correcto
echo "👤 Configurando propietario..."
chown -R www-data:www-data /var/www/html/storage
chown -R www-data:www-data /var/www/html/bootstrap/cache

# Establecer permisos
echo "🔐 Configurando permisos..."
chmod -R 777 /var/www/html/storage
chmod -R 777 /var/www/html/bootstrap/cache

# Configurar archivo de log específicamente
echo "📝 Configurando archivo de log..."
touch /var/www/html/storage/logs/laravel.log
chown www-data:www-data /var/www/html/storage/logs/laravel.log
chmod 664 /var/www/html/storage/logs/laravel.log

# Verificar configuración
echo "✅ Verificando permisos..."
ls -la /var/www/html/storage/
ls -la /var/www/html/storage/logs/

echo "🎉 ¡Configuración de permisos completada!"