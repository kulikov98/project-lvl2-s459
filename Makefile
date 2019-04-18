install:
	composer install
lint:
	composer run-script phpcs -- --standard=PSR2 src bin tests
test:
	composer run-script phpunit tests
genplain:
	bin/gendiff --format plain tests/fixtures/nested/before.json tests/fixtures/nested/after.json
gentext:
	bin/gendiff --format text tests/fixtures/nested/before.json tests/fixtures/nested/after.json
genjson:
	bin/gendiff --format json tests/fixtures/nested/before.json tests/fixtures/nested/after.json