#!/bin/bash

set -e

echo "🎨 Rebuilding frontend assets..."
echo ""

# Check if container is running
if ! docker-compose ps | grep -q "app.*Up"; then
    echo "❌ Error: App container is not running!"
    echo "Run 'make up' first to start the project"
    exit 1
fi

# Clear Symfony cache
echo "🧹 Clearing Symfony cache..."
docker-compose exec -T app php bin/console cache:clear

# Install assets (if symfony/asset package is installed)
if docker-compose exec -T app php bin/console list | grep -q "assets:install"; then
    echo "📦 Installing assets..."
    docker-compose exec -T app php bin/console assets:install --symlink public
fi

# Check if npm is available and package.json exists
if [ -f "./app/package.json" ]; then
    echo "📚 Installing npm dependencies..."
    docker-compose exec -T app npm install

    echo "🔨 Building frontend assets..."
    docker-compose exec -T app npm run build
else
    echo "ℹ️  No package.json found, skipping npm build"
fi

echo ""
echo "✅ Frontend rebuild complete!"
echo "🌐 Application: http://dev.wolfly.localhost"
