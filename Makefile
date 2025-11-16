.DEFAULT_GOAL := help
.PHONY: help up init down ssh migration

help: ## Show this help message
	@echo "Wolfly.gg - Available Commands:"
	@echo ""
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-15s\033[0m %s\n", $$1, $$2}'
	@echo ""
	@echo "Examples:"
	@echo "  make init       - Initial setup (clean install)"
	@echo "  make up         - Start the project"
	@echo "  make down       - Stop the project"
	@echo "  make ssh        - Connect to app container"
	@echo "  make migration  - Create a new database migration"

up: ## Start the project
	@bash scripts/make/up.sh

init: ## Initial project setup (clean install)
	@bash scripts/make/init.sh

down: ## Stop the project
	@bash scripts/make/down.sh

ssh: ## Connect to app container via bash
	@bash scripts/make/ssh.sh

migration: ## Create a new database migration
	@bash scripts/make/migration.sh
