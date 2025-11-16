#!/bin/bash

set -e

echo "📚 Generating Code Documentation..."
echo ""

# Check if phpdoc.xml exists
if [ ! -f "phpdoc.xml" ]; then
    echo "❌ Error: phpdoc.xml not found!"
    echo "Run this command from the project root directory"
    exit 1
fi

# Create docs directory if it doesn't exist
mkdir -p docs/code

# Run phpDocumentor in Docker
echo "🔨 Running phpDocumentor..."
docker run --rm \
    -v "$(pwd):/data" \
    phpdoc/phpdoc:3 \
    --config=/data/phpdoc.xml \
    --target=/data/docs/code \
    --cache-folder=/data/app/var/cache/phpdoc

echo ""
echo "✅ Documentation generated successfully!"
echo ""
echo "📖 View documentation:"
echo "   • Open: docs/code/index.html"
echo "   • Or run: make docs-serve"
echo ""
echo "🌐 Documentation Hub: docs/index.html"
