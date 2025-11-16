#!/bin/bash

set -e

echo "🛑 Stopping Wolfly.gg..."
echo ""

# Stop Docker containers
docker-compose down

echo ""
echo "✅ Project stopped successfully!"
echo "Run 'make up' to start the project again"
