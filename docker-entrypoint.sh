#!/bin/sh
set -e

# cria o diretório de logs dentro do volume mapeado
mkdir -p /var/www/html/storage/logs
chown -R www-data:www-data /var/www/html/storage
chmod -R 775 /var/www/html/storage/logs

# passa o controle para o entrypoint original, que vai 
# terminar iniciando o Apache
exec docker-php-entrypoint "$@"
