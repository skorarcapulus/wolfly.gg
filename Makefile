.DEFAULT_GOAL := help
.PHONY: help up init down ssh migration console frontend

help: ## Show this help message
	@echo "Wolfly.gg - Available Commands:"
	@echo ""
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-15s\033[0m %s\n", $$1, $$2}'
	@echo ""
	@echo "Examples:"
	@echo "  make init              - Initial setup (clean install)"
	@echo "  make up                - Start the project"
	@echo "  make down              - Stop the project"
	@echo "  make ssh               - Connect to app container"
	@echo "  make migration         - Create a new database migration"
	@echo "  make console           - Show Symfony console commands"
	@echo "  make console cache:clear - Execute Symfony console command"
	@echo "  make frontend          - Rebuild frontend assets"

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

console: ## Execute Symfony console commands (usage: make console [command])
	@bash scripts/make/console.sh $(filter-out $@,$(MAKECMDGOALS))

frontend: ## Rebuild frontend assets and clear cache
	@bash scripts/make/frontend.sh

# Catch-all target to prevent "No rule to make target" errors when passing arguments to console
%:
	@:
