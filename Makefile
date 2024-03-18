START_FLAG ?= -d
BUILD_FLAG ?= --build

init: start
	@echo "Starting project initialization..."
	@echo "Waiting for MySQL to be ready..."
	@until docker-compose exec -T db mysqladmin ping -h "db" --silent; do \
		echo 'Waiting for MySQL...' ; \
		sleep 1 ; \
	done
	@echo "Installing Composer dependencies..."
	@docker-compose exec -T app composer install
	@echo "Running migrations and seeders..."
	@docker-compose exec -T app php artisan migrate:fresh --seed
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
