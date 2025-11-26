# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

This is a Symfony 7.3 application running on PHP 8.2 with PostgreSQL 13 as the database. The project uses Docker for containerization and is configured for both development and production environments.

## Architecture

**Technology Stack:**
- PHP 8.2 with Symfony 7.3 framework
- PostgreSQL 13 database
- Nginx web server with PHP-FPM
- Docker multi-stage builds (development and production targets)

**Directory Structure:**
- `app/` - Main Symfony application directory
  - `src/` - Application source code (PSR-4 autoloaded as `App\` namespace)
  - `config/` - Symfony configuration files and service definitions
  - `public/` - Web root containing `index.php` entry point
  - `bin/console` - Symfony console CLI tool
  - `var/` - Cache and logs (not committed to git)
  - `docker/` - Docker configuration files (nginx.conf, start.sh, supervisor.conf)
- `scripts/` - Deployment or utility scripts (currently empty)
- `docs/` - Project documentation (currently empty)

**Key Configuration:**
- Symfony services use autowiring and autoconfiguration by default
- Database connection uses environment variables from `.env` file
- Multi-stage Dockerfile with separate development and production builds
- Production build includes OPcache optimization and supervisor for process management

## Development Commands

**Make Commands (Recommended):**
```bash
# Show all available commands
make help

# Initial project setup (clean install)
make init

# Start the project
make up

# Stop the project
make down

# Connect to app container
make ssh

# Create a new database migration
make migration

# Execute Symfony console commands
make console                    # Show all console commands
make console cache:clear        # Clear cache
make console debug:router       # Debug routes
make console list               # List all commands

# Rebuild frontend assets
make frontend

# Generate and serve documentation
make docs                       # Generate code documentation
make docs-serve                 # Serve docs on http://localhost:8080
```

**Direct Docker Operations:**
```bash
# Start development environment
docker-compose up -d

# Build with specific target
TARGET=development docker-compose build
TARGET=production docker-compose build

# View logs
docker-compose logs -f app
```

**Symfony Console (inside container):**
```bash
# Execute console commands via make ssh, then:
php bin/console

# Or directly:
docker-compose exec app php bin/console cache:clear
docker-compose exec app php bin/console debug:router
docker-compose exec app php bin/console debug:container
```

**Database Access:**
```bash
# Connect to PostgreSQL database
docker-compose exec db psql -U symfony symfony_db
```

## Environment Configuration

The application uses a two-tier environment variable system following Symfony best practices:

**`.env` (Committed to Git):**
- Contains default values and documentation for all environment variables
- Safe to commit - no sensitive data
- Serves as template and documentation for all developers
- Variables: `APP_ENV`, `TARGET`, `DB_HOST`, `DB_PORT`, `DB_USER`, `DB_PASSWORD`, `DB_NAME`, `APP_SECRET`

**`.env.local` (NOT Committed - in .gitignore):**
- Overrides values from `.env` for local development
- Contains your personal/sensitive configuration
- Automatically loaded by Docker Compose (optional, not required)
- Create this file to customize your local setup without affecting other developers

**Environment Variables:**
- `APP_ENV` - Application environment (dev, prod, test)
- `TARGET` - Docker build target (development, production)
- `APP_SECRET` - Symfony application secret (change in production!)
- Database connection: `DB_HOST`, `DB_PORT`, `DB_USER`, `DB_PASSWORD`, `DB_NAME`

**Docker Compose Loading Order:**
1. `.env` is loaded first (required)
2. `.env.local` overrides `.env` values (optional)
3. Values are used for variable substitution in `docker-compose.yml`

The `DATABASE_URL` is automatically constructed in docker-compose.yml from individual database variables.

**Example Setup:**
```bash
# Use default values from .env (works out of the box)
make up

# Or customize by creating .env.local:
cp .env .env.local
# Edit .env.local with your custom values
make up
```

## Service Ports

- Application: http://dev.wolfly.localhost
- PostgreSQL: localhost:5432

## Docker Architecture

**Development Stage:**
- Based on `php:8.2-fpm-alpine`
- Includes development tools (git, Node.js, npm)
- Mounts source code as volumes for live reloading
- Runs nginx + PHP-FPM via `/start.sh` script

**Production Stage:**
- Optimized with `--no-dev` composer dependencies
- Includes OPcache with performance tuning
- Uses supervisor to manage nginx and PHP-FPM processes
- No volume mounts; code is copied into image

## Database Connection

PostgreSQL is configured via environment variables with the default connection:
- Host: db (Docker service name)
- Port: 5432
- User: symfony
- Password: symfony
- Database: symfony_db

## Documentation

**Code Documentation:**
- Generated with phpDocumentor from PHPDoc comments
- Run `make docs` to generate documentation
- Run `make docs-serve` to view at http://localhost:8080
- Always document classes, methods, and parameters with PHPDoc blocks

**API Documentation (Future):**
- When API Platform is installed, API docs will be automatically available at http://dev.wolfly.localhost/api/docs
- No additional configuration needed - API Platform generates OpenAPI/Swagger docs automatically

## Adding New Features

When creating new Symfony components:
1. Controllers go in `app/src/Controller/`
2. Services are auto-registered from `app/src/` with autowiring enabled
3. Routes can be defined via attributes on controllers or in `app/config/routes.yaml`
4. Configuration goes in `app/config/packages/` for bundle-specific settings
5. Always add PHPDoc comments for automatic documentation generation
