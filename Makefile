START_FLAG ?= -d
BUILD_FLAG ?= --build

init: start
	@echo "Initializing project..."
	@docker-compose exec -T app php artisan migrate:fresh --seed
	@echo "Initialization complete."

start:
	docker-compose up $(START_FLAG)

start-build:
	docker-compose up $(START_FLAG) $(BUILD_FLAG)

build:
	docker-compose build --no-cache

stop:
	docker-compose down

enter-app:
	docker-compose exec app bash

tail-log:
	docker-compose exec -T app tail -f /var/www/html/storage/logs/laravel.log
