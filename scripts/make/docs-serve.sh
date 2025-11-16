#!/bin/bash

set -e

# Check if documentation exists
if [ ! -d "docs/code" ] || [ ! -f "docs/code/index.html" ]; then
    echo "❌ Error: Documentation not found!"
    echo "Run 'make docs' first to generate documentation"
    exit 1
fi

echo "🌐 Starting Documentation Server..."
echo ""
echo "📖 Documentation available at:"
echo "   • Documentation Hub: http://localhost:8080"
echo "   • Code Docs: http://localhost:8080/code/"
echo ""
echo "Press Ctrl+C to stop the server"
echo ""

# Start simple HTTP server
cd docs && python3 -m http.server 8080
