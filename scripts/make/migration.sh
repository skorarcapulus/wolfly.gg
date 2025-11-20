#!/bin/bash

set -e

echo "🗄️  Creating new database migration..."
echo ""

# Check if container is running
if ! docker-compose ps | grep -q "app.*Up"; then
    echo "❌ Error: App container is not running!"
    echo "Run 'make up' first to start the project"
    exit 1
fi

# Check if doctrine is installed
if ! docker-compose exec -T --user appuser app php bin/console list | grep -q "doctrine:migrations"; then
    echo "❌ Error: Doctrine Migrations is not installed!"
    echo ""
    echo "To install Doctrine Migrations, run:"
    echo "  make ssh"
    echo "  composer require symfony/orm-pack"
    echo "  composer require --dev symfony/maker-bundle"
    exit 1
fi

# Ask for migration name
read -p "Enter migration name (optional): " migration_name

if [ -z "$migration_name" ]; then
    # Create migration without name
    docker-compose exec -T --user appuser app php bin/console doctrine:migrations:diff
else
    # Create migration with name
    docker-compose exec -T --user appuser app php bin/console make:migration
fi

echo ""
echo "✅ Migration created successfully!"
echo ""
echo "Next steps:"
echo "  - Review the migration file in app/migrations/"
echo "  - Run 'make console doctrine:migrations:migrate' to apply"
