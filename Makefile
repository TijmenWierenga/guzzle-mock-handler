phpunit:
	docker run -it --rm -v $$(pwd):/app -w /app php:7.4-alpine vendor/bin/phpunit