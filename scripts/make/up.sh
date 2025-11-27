#!/bin/bash

set -e

echo "🚀 Starting Wolfly.gg..."
echo ""

# Start Docker containers
docker-compose up -d

# Check if we're in dev environment and reset database
APP_ENV=$(grep APP_ENV .env | cut -d '=' -f2)
if [ "$APP_ENV" = "dev" ]; then
    echo "🗑️  Resetting database (DEV environment)..."
    docker-compose exec -T app php bin/console doctrine:database:drop --force --if-exists
    docker-compose exec -T app php bin/console doctrine:database:create
fi

# Run database migrations when migrations folder is not empty
echo "🛠️  Running database migrations..."
MIGRATION_COUNT=$(docker-compose exec -T app sh -c "ls -1 migrations/*.php 2>/dev/null | wc -l")
if [ "$MIGRATION_COUNT" -gt 0 ]; then
    docker-compose exec -T app php bin/console doctrine:migrations:migrate --no-interaction
else
    echo "No migrations to run."
fi


# Clear Symfony cache
echo "🧹 Clearing Symfony cache..."
docker-compose exec -T app php bin/console cache:clear

# Rebuild frontend assets by calling the frontend make script
echo "🎨 Rebuilding frontend assets..."
./scripts/make/frontend.sh

# Delete generated templates
echo "🗑️  Deleting generated templates..."
docker-compose exec -T app rm -rf templates/_generated/


echo ""
echo "✅ Project started successfully!"
echo "🌐 Application: http://dev.wolfly.localhost"
echo "🐘 Database: localhost:5432"
echo ""
echo "Run 'make down' to stop the project"
echo "Run 'make ssh' to connect to the app container"
