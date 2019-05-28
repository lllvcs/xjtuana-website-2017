#!/bin/sh

mkdir -p /docker/www/bootstrap/cache /docker/www/storage

chown -R www-data:www-data /docker/www/bootstrap/cache /docker/www/storage

mkdir -p /docker/log /docker/www/storage/logs/stdout/

supervisord -c /docker/supervisord.conf

"$@"
