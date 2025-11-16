#!/bin/bash

set -e

echo "🔌 Connecting to app container..."
echo ""

# Check if container is running
if ! docker-compose ps | grep -q "app.*Up"; then
    echo "❌ Error: App container is not running!"
    echo "Run 'make up' first to start the project"
    exit 1
fi

# Connect to container
docker-compose exec app /bin/sh
