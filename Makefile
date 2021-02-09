PHP_VERSION = 8.0
DOCKER_RUN = docker run -it --rm -v $$(pwd):/app -w /app php:${PHP_VERSION}-alpine

test: phpcs phpstan phpunit psalm
phpunit:
	${DOCKER_RUN} vendor/bin/phpunit
phpstan:
	${DOCKER_RUN} vendor/bin/phpstan analyze
psalm:
	${DOCKER_RUN} vendor/bin/psalm
phpcs:
	${DOCKER_RUN} vendor/bin/phpcs src tests --standard=PSR12