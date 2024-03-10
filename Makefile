START_FLAG ?= -d
BUILD_FLAG ?= --build

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
