#!/bin/bash

set -e

echo "🔧 Initializing Wolfly.gg (Clean Install)..."
echo ""

# Stop and remove existing containers
echo "📦 Cleaning up existing containers..."
docker-compose down -v

# Build containers from scratch
echo "🏗️  Building Docker containers..."
docker-compose build --no-cache

# Start containers
echo "🚀 Starting containers..."
docker-compose up -d

# Wait for database to be ready
echo "⏳ Waiting for database to be ready..."
sleep 5

# Install Composer dependencies
echo "📚 Installing Composer dependencies..."
docker-compose exec -T app composer install --no-interaction --prefer-dist --optimize-autoloader

# Clear Symfony cache
echo "🧹 Clearing Symfony cache..."
docker-compose exec -T app php bin/console cache:clear

# Set proper permissions
echo "🔐 Setting proper permissions..."
docker-compose exec -T app chown -R www-data:www-data var/

# Create the database
echo "🗄️  Creating the database..."
docker-compose exec -T app php bin/console doctrine:database:create --if-not-exists

echo ""
echo "✅ Project initialized successfully!"
echo "🌐 Application: http://dev.wolfly.localhost"
echo "🐘 Database: localhost:5432"
echo ""
echo "Next steps:"
echo "  - Run 'make migration' to create database migrations"
echo "  - Run 'make ssh' to connect to the app container"
echo "  - Run 'make down' to stop the project"
