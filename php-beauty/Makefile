cnf ?= .env
include .env
export $(shell sed 's/=.*//' $(cnf))

.PHONY: help

help:
	@awk 'BEGIN {FS = ":.*?## "} /^[a-zA-Z_-]+:.*?## / {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2}' $(MAKEFILE_LIST)

.DEFAULT_GOAL := help

init-project: up composer-install create-db pimcore-install pimcore-rebuild-classes

create-db: ## Create database
	docker-compose exec db mysql -pphp-beauty-pwd -e "CREATE DATABASE IF NOT EXISTS \`${MYSQL_DATABASE}\`"

composer-install: ## Composer install
	docker-compose exec php-fpm php -d memory_limit=-1 /usr/bin/composer install

pimcore-install: ## Pimcore install
	docker-compose exec php-fpm php ./vendor/bin/pimcore-install \
        										  --ignore-existing-config \
        										  --admin-username=admin \
        										  --admin-password=admin \
        										  --mysql-host-socket=db \
        										  --mysql-username=${MYSQL_USER} \
        										  --mysql-password=${MYSQL_PASSWORD} \
        										  --mysql-database=${MYSQL_DATABASE} \
        										  --mysql-port=${MYSQL_PORT} \
        										  --no-interaction

build: ## Build docker containers
	docker-compose build

up: ## Up docker containers
	docker-compose up -d

stop: ## Stop docker containers
	docker-compose stop

php: ## Enter to php docker container
	docker-compose exec php-fpm bash

mysql: ## Enter to mysql docker container
	docker-compose exec db bash

ps: ## Show started containers
	docker-compose ps

pimcore-cache-clear: ## Clear all caches in pimcore
	docker-compose exec php-fpm php bin/console cache:clear
	docker-compose exec php-fpm php bin/console pimcore:cache:clear

pimcore-rebuild-classes: ## Rebuild classes
	docker-compose exec php-fpm php bin/console pimcore:deployment:classes-rebuild
	docker-compose exec php-fpm php bin/console pimcore:deployment:classes-rebuild -c

define print_info
	@echo "\033[0;32m$(1)\033[0m"
endef
