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

The application uses environment variables defined in `.env`:
- `APP_ENV` - Application environment (dev, prod, test)
- `TARGET` - Docker build target (development, production)
- Database connection: `DB_HOST`, `DB_PORT`, `DB_USER`, `DB_PASSWORD`, `DB_NAME`

The `DATABASE_URL` is automatically constructed in docker-compose.yml from individual database variables.

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

## Adding New Features

When creating new Symfony components:
1. Controllers go in `app/src/Controller/`
2. Services are auto-registered from `app/src/` with autowiring enabled
3. Routes can be defined via attributes on controllers or in `app/config/routes.yaml`
4. Configuration goes in `app/config/packages/` for bundle-specific settings
