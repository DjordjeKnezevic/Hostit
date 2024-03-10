#!/bin/bash

chown -R www-data:www-data /var/www/html/storage
chmod -R 775 /var/www/html/storage

# for development
npm run watch &

php-fpm
