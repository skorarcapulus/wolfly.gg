#!/bin/sh

# Start nginx in background
nginx -g 'daemon off;' &

# Start PHP-FPM in foreground
exec php-fpm