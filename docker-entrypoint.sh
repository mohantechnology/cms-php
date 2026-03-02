#!/bin/sh
set -e
# Ensure upload directory exists and is writable by Apache (www-data)
mkdir -p /var/www/html/upload
chown -R www-data:www-data /var/www/html/upload
chmod -R 775 /var/www/html/upload
exec apache2-foreground
