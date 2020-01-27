PHP_VERSION = 7.4
DOCKER_RUN = docker run -it --rm -v $$(pwd):/app -w /app php:${PHP_VERSION}-alpine

test: phpstan phpunit psalm
phpunit:
	${DOCKER_RUN} vendor/bin/phpunit
phpstan:
	${DOCKER_RUN} vendor/bin/phpstan analyze
psalm:
	${DOCKER_RUN} vendor/bin/psalm