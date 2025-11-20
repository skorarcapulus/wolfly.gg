# Multi-stage Dockerfile for Symfony project

# Development stage
FROM php:8.2-fpm-alpine AS development

# Build arguments for user/group ID matching
ARG USER_ID=1000
ARG GROUP_ID=1000

# Install system dependencies
RUN apk add --no-cache \
    git \
    curl \
    libpng-dev \
    libzip-dev \
    zip \
    unzip \
    oniguruma-dev \
    postgresql-dev \
    nodejs \
    npm \
    nginx \
    shadow \
    icu-dev

# Install PHP extensions
RUN docker-php-ext-install \
    pdo \
    pdo_pgsql \
    mbstring \
    zip \
    bcmath \
    gd \
    intl

# Create appuser with matching host UID/GID
RUN deluser --remove-home www-data 2>/dev/null || true && \
    addgroup -g ${GROUP_ID} appuser && \
    adduser -D -u ${USER_ID} -G appuser appuser && \
    mkdir -p /home/appuser/.composer && \
    chown -R appuser:appuser /home/appuser

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /srv/app

# Change ownership of working directory
RUN chown -R appuser:appuser /srv/app

# Switch to appuser for composer operations
USER appuser

# Copy composer files
COPY --chown=appuser:appuser composer.json composer.lock symfony.lock ./

# Install dependencies
RUN composer install --no-scripts --no-autoloader

# Copy application code
COPY --chown=appuser:appuser . .

# Generate autoloader
RUN composer dump-autoload --optimize

# Create cache and log directories with proper permissions
RUN mkdir -p var/cache var/log

# Switch back to root for system configuration
USER root

# Configure Nginx and PHP-FPM for development
COPY docker/nginx.conf /etc/nginx/nginx.conf
COPY docker/php-fpm.conf /usr/local/etc/php-fpm.d/www.conf
COPY docker/start.sh /start.sh
RUN chmod +x /start.sh

# Ensure appuser can write to var directory and nginx directories
RUN chown -R appuser:appuser /srv/app/var && \
    mkdir -p /var/cache/nginx /var/log/nginx /var/lib/nginx && \
    chown -R appuser:appuser /var/cache/nginx /var/log/nginx /var/lib/nginx && \
    touch /run/nginx.pid && \
    chown appuser:appuser /run/nginx.pid

# Expose port
EXPOSE 80

# Start nginx and php-fpm (will run as root, but workers as appuser)
CMD ["/start.sh"]

# Production stage
FROM php:8.2-fpm-alpine AS production

# Install system dependencies
RUN apk add --no-cache \
    libpng-dev \
    libzip-dev \
    zip \
    unzip \
    oniguruma-dev \
    postgresql-dev \
    nginx \
    supervisor \
    icu-dev

# Install PHP extensions
RUN docker-php-ext-install \
    pdo \
    pdo_pgsql \
    mbstring \
    zip \
    bcmath \
    gd \
    opcache \
    intl

# Configure OPcache
RUN echo "opcache.enable=1" >> /usr/local/etc/php/conf.d/opcache.ini && \
    echo "opcache.memory_consumption=128" >> /usr/local/etc/php/conf.d/opcache.ini && \
    echo "opcache.max_accelerated_files=10000" >> /usr/local/etc/php/conf.d/opcache.ini && \
    echo "opcache.revalidate_freq=2" >> /usr/local/etc/php/conf.d/opcache.ini

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /srv/app

# Copy composer files
COPY composer.json composer.lock symfony.lock ./

# Install production dependencies
RUN composer install --no-dev --optimize-autoloader --no-scripts

# Copy application code
COPY . .

# Generate optimized autoloader
RUN composer dump-autoload --optimize --no-dev

# Create cache and log directories with proper permissions
RUN mkdir -p var/cache var/log && \
    chown -R www-data:www-data var/ && \
    chmod -R 755 var/

# Clear and warm up cache for production
RUN APP_ENV=prod composer dump-env prod

# Configure Nginx
COPY docker/nginx.conf /etc/nginx/nginx.conf
COPY docker/supervisor.conf /etc/supervisor/conf.d/supervisord.conf

# Expose port
EXPOSE 80

# Start supervisor
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]