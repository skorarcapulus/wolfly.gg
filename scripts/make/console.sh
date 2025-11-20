#!/bin/bash

set -e

# Check if container is running
if ! docker-compose ps | grep -q "app.*Up"; then
    echo "❌ Error: App container is not running!"
    echo "Run 'make up' first to start the project"
    exit 1
fi

# If no arguments provided, show console help
if [ $# -eq 0 ]; then
    echo "🎮 Symfony Console"
    echo ""
    docker-compose exec --user appuser app php bin/console
else
    # Execute console command with all arguments
    echo "🎮 Executing: php bin/console $*"
    echo ""
    docker-compose exec --user appuser app php bin/console "$@"
fi
