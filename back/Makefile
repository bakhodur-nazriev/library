# create network docker network create library_network

up:
	docker-compose up -d
cp_env:
#   docker-compose run --rm php_library cp ./.env.example ./.env
composer_update:
	docker-compose run --rm composer_library composer update
composer_install:
	docker-compose run --rm composer_library composer install
key_gen:
	docker-compose run --rm artisan_library key:generate
migrate:
	docker-compose run --rm artisan_library migrate
seed:
	docker-compose run --rm artisan_library db:seed
queue:
	docker-compose run --rm artisan_library queue:work
npm_install:
	docker-compose run --rm node npm install
npm_run_prod:
	docker-compose run --rm node npm run build
down:
	docker-compose down
optimize_clear:
	docker-compose run --rm artisan_library optimize:clear

# Shortcut to Run Everything
all: up cp_env composer_install key_gen migrate seed npm_install
