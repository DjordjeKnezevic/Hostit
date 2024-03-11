START_FLAG ?= -d
BUILD_FLAG ?= --build

init: start
	@echo "Starting project initialization..."
	@echo "Installing Composer dependencies..."
	@docker-compose exec -T app composer install
	@echo "Running migrations..."
	@docker-compose exec -T app php artisan migrate --force
	@echo "Seeding the database..."
	@docker-compose exec -T app php artisan db:seed --force
	@echo "Linking storage..."
	@docker-compose exec -T app php artisan storage:link
	@echo "Project initialization complete."

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
