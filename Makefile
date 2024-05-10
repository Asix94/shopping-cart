USER = $(shell id -u):$(shell id -g)
APP_ENV = local
PROJECT_NAME = promofarma
COMPOSE = USER=${USER} docker-compose -p $(PROJECT_NAME) -f docker/docker-compose.yml
FPM_CONTAINER_NAME = promofarma_php_fpm

.PHONY: tests

prepare: build up vendor-install db-fresh test/db-fresh

build:
	DOCKER_BUILDKIT=1 $(COMPOSE) build

vendor-install:
	@docker exec -it $(FPM_CONTAINER_NAME) composer install

up:
	$(COMPOSE) up -d

down:
	$(COMPOSE) down

bash:
	@docker exec -it $(FPM_CONTAINER_NAME) bash

tests:
	@docker exec -it $(FPM_CONTAINER_NAME) php bin/phpunit

db-create-migration:
	@docker exec -it $(FPM_CONTAINER_NAME) php bin/console -e $(APP_ENV) doctrine:migrations:generate

db-create:
	@docker exec -it $(FPM_CONTAINER_NAME) php bin/console -e $(APP_ENV) doctrine:database:create

db-reset:
	@docker exec -it $(FPM_CONTAINER_NAME) php bin/console -e $(APP_ENV) doctrine:database:drop --if-exists -f

db-migrate:
	@docker exec -it $(FPM_CONTAINER_NAME) php bin/console -e $(APP_ENV) doctrine:migrations:migrate -n

db-fresh: db-reset db-create db-migrate

test/db-fresh:
	@make APP_ENV=test db-fresh