# Wolfly.GG

**Create custom stream overlays with HTML, CSS & JavaScript**

Wolfly.GG is a self-hosted platform for streamers who want to create professional stream overlays without expensive tools or complicated video editing software. Build your custom overlays using web technologies you already know.

## Overview

Traditional stream overlay creation requires either:
- Expensive pre-made overlay packages
- Complex Adobe After Effects or DaVinci Resolve tutorials with steep learning curves
- Ongoing subscription costs for Adobe Creative Cloud

Wolfly.GG offers a different approach: a self-hosted platform where you can create, customize, and manage your stream overlays using HTML, CSS, and JavaScript. Full control, zero recurring costs.

## Features

- **Web-Based Overlay Creation**: Build overlays using HTML, CSS, and JavaScript
- **Self-Hosted**: Complete control over your data and infrastructure
- **Source Available**: Transparent, customizable, and community-driven
- **Modern Tech Stack**: Built with Symfony 7.3, PHP 8.2, and PostgreSQL
- **Docker Support**: Easy deployment and development setup
- **SaaS-Ready Architecture**: Designed to support future hosted service offering

## Technology Stack

- **Backend**: PHP 8.2 with Symfony 7.3 framework
- **Database**: PostgreSQL 13
- **Web Server**: Nginx with PHP-FPM
- **Containerization**: Docker with multi-stage builds
- **Frontend**: Symfony UX Twig Components, HTML, CSS, JavaScript

## Prerequisites

- Docker and Docker Compose
- Make (for convenient command execution)
- Git

## Quick Start

### 1. Clone the Repository

```bash
git clone https://github.com/skorarcapulus/wolfly.gg
cd wolfly.gg
```

### 2. Initial Setup

```bash
make init
```

This will:
- Build Docker containers from scratch
- Set up the PostgreSQL database
- Install all dependencies
- Configure the development environment
- Run database migrations

### 3. Start the Application

```bash
make up
```

Access the application at: **http://dev.wolfly.localhost**

**Note**: In development mode, the database is automatically reset on every `make up` to ensure a clean state.

## Development

### Available Commands

```bash
make help              # Show all available commands
make up                # Start the project
make down              # Stop the project
make ssh               # Connect to app container
make migration         # Create a new database migration
make console           # Show Symfony console commands
make console <cmd>     # Execute Symfony console command
make frontend          # Rebuild frontend assets
make docs              # Generate code documentation
make docs-serve        # Serve docs at http://localhost:8080
```

### Environment Configuration

The project uses a two-tier environment variable system:

**`.env` (committed to repository)**
- Contains safe default values and serves as documentation
- All developers share these base values
- Safe to commit - no sensitive data

**`.env.local` (optional, not committed)**
- Overrides `.env` values for your local setup
- Add your personal/sensitive configuration here
- Automatically loaded by Docker Compose if it exists
- Ignored by git

**Available Variables:**

```env
APP_ENV=dev              # Application environment (dev, prod, test)
TARGET=development       # Docker build target (development, production)
APP_SECRET=...          # Symfony secret (change in production!)
DB_HOST=db              # Database host
DB_PORT=5432            # Database port
DB_USER=symfony         # Database user
DB_PASSWORD=symfony     # Database password
DB_NAME=symfony_db      # Database name
USER_ID=1000            # Docker user ID (defaults to 1000)
GROUP_ID=1000           # Docker group ID (defaults to 1000)
```

**Customize Your Setup:**

```bash
# Option 1: Use defaults (just works)
make up

# Option 2: Customize locally
cp .env .env.local
# Edit .env.local with your values
make up
```

### Database Management

**Automatic Database Reset (DEV only)**:
- Running `make up` or `make init` automatically resets the database in development mode
- This ensures you always start with a clean database state
- Production environments preserve the database

**Manual Database Operations**:
```bash
make console doctrine:database:drop --force    # Drop database
make console doctrine:database:create          # Create database
make migration                                 # Create new migration
make console doctrine:migrations:migrate       # Run migrations
```

### Coding Standard

- Follow PSR-12 coding standards for PHP
- Use Symfony best practices and conventions
- Add PHPDoc comments for classes, methods, and parameters
- Run `make docs` to generate code documentation

### Dependency Management

**Dependabot Configuration:**
- Automatically checks for dependency updates weekly
- Configured for Composer (PHP dependencies) and Docker base images
- Creates pull requests for available updates
- Configuration: `.github/dependabot.yml`

### Docker Architecture

**Development Mode**:
- Live code reloading with volume mounts
- Includes development tools (git, Node.js, npm)
- Xdebug support for debugging

**Production Mode**:
- Optimized with OPcache
- No development dependencies
- Supervisor process management
- Minimal attack surface

## Documentation

### Code Documentation

Generate and view PHP code documentation:

```bash
make docs        # Generate documentation
make docs-serve  # Serve at http://localhost:8080
```

### API Documentation

Once API Platform is integrated, API documentation will be automatically available at:
**http://dev.wolfly.localhost/api/docs**

## Deployment

### Self-Hosted Deployment

1. Set `APP_ENV=prod` and `TARGET=production` in `.env`
2. Configure production database credentials
3. Build and deploy:

```bash
docker-compose build
docker-compose up -d
```

### SaaS Deployment

SaaS deployment architecture is planned for future releases.

## Roadmap

Follow the Codecks board for upcoming features and improvements: [Codecks Board](https://open.codecks.io/wolflygg)

## Contributing

Contributions are welcome! Please feel free to submit issues and pull requests.

## License

📄 License: Wolfly Public License (WPL-1.0)

This project is licensed under the Wolfly Public License (WPL-1.0).

✔ Allowed

- Self-hosting for personal or internal use
- Private forks
- Private modifications
- Pull requests to the official repository

❌ Not Allowed

- Commercial use
- Selling the software or services based on it
- Redistributing or re-releasing the code
- Offering the software as a hosted service
- Publishing modified versions or forks

🔐 SaaS Rights

Only Raphael Sundermann (operating as Wolfly.gg) is allowed to offer a commercial SaaS version of this project.

For details, see the full license in the LICENSE file.

## Support

For questions, issues, or feature requests, please open an issue on the repository.

---

**Built with ❤️ and passion for the streaming community by Skorar**
