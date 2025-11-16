#!/bin/bash

set -e

echo "🚀 Starting Wolfly.gg..."
echo ""

# Start Docker containers
docker-compose up -d

echo ""
echo "✅ Project started successfully!"
echo "🌐 Application: http://dev.wolfly.localhost"
echo "🐘 Database: localhost:5432"
echo ""
echo "Run 'make down' to stop the project"
echo "Run 'make ssh' to connect to the app container"
