.PHONY: help setup up down build shell migrate seed test fresh logs

COMPOSE ?= docker compose

help:
	@echo "Ecommerce platform — Docker helpers"
	@echo ""
	@echo "  make setup   First-time bootstrap (build, install, migrate, seed, assets)"
	@echo "  make up      Start containers"
	@echo "  make down    Stop containers"
	@echo "  make shell   Open a shell in the app container"
	@echo "  make test    Run Pest tests"
	@echo "  make fresh   Reset DB and reseed"
	@echo "  make logs    Tail container logs"

setup:
	@bash ./docker/setup.sh

up:
	$(COMPOSE) up -d --build

down:
	$(COMPOSE) down

build:
	$(COMPOSE) build --no-cache

shell:
	$(COMPOSE) exec laravel.test bash

migrate:
	$(COMPOSE) exec laravel.test php artisan migrate --force

seed:
	$(COMPOSE) exec laravel.test php artisan db:seed --force

fresh:
	$(COMPOSE) exec laravel.test php artisan migrate:fresh --seed --force

test:
	$(COMPOSE) exec laravel.test php artisan test --compact

logs:
	$(COMPOSE) logs -f
