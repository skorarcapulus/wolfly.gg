# Wolfly.gg Documentation

Welcome to the Wolfly.gg documentation hub! This directory contains all project documentation.

## 📚 Available Documentation

### Code Documentation
Automatically generated documentation from PHP docblocks using phpDocumentor.

**Location:** [`/docs/code/`](./code/index.html)

**Generate:** Run `make docs` to generate the latest code documentation.

### API Documentation
API documentation will be automatically available once API Platform is installed.

**Location:** http://dev.wolfly.localhost/api/docs (available after API Platform installation)

**Features:**
- Interactive OpenAPI/Swagger UI
- Try out API endpoints directly in the browser
- Automatically updated when you add/modify API resources

## 🚀 Quick Start

```bash
# Generate code documentation
make docs

# Serve documentation locally
make docs-serve

# Open in browser
# Code Docs: http://localhost:8080
# API Docs: http://dev.wolfly.localhost/api/docs (after API Platform is installed)
```

## 📖 Documentation Standards

### PHP Docblocks
Always document your classes, methods, and properties:

```php
/**
 * Short description of the class
 *
 * Longer description if needed. This can span
 * multiple lines and include examples.
 *
 * @package App\Controller
 * @author Your Name
 */
class ExampleController
{
    /**
     * Short description of the method
     *
     * @param string $parameter Description of parameter
     * @return Response Description of return value
     * @throws \Exception When something goes wrong
     */
    public function exampleAction(string $parameter): Response
    {
        // method code
    }
}
```

### API Resources (Future)
When using API Platform, document your API resources with OpenAPI attributes:

```php
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;

#[ApiResource(
    description: 'A user resource',
    operations: [
        new Get(),
        new GetCollection(),
    ]
)]
class User
{
    // entity properties
}
```

## 🔄 Automatic Updates

Code documentation is generated from your source code. Run `make docs` after:
- Adding new classes or methods
- Updating docblocks
- Major code changes

API documentation updates automatically when the application is running.
