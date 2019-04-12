install:
	composer install
lint:
	composer run-script phpcs -- --standard=PSR2 src bin tests
test:
	composer run-script phpunit tests
gendiff:
	bin/gendiff tests/fixtures/nested/before.json tests/fixtures/nested/after.json