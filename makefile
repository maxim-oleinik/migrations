.PHONY : build test

# Сборка проекта (Default)
build: vendor/composer/installed.json
	composer validate --no-check-all --strict
	composer dump

vendor/composer/installed.json: composer.json
	composer update


# Тесты
phpunit.xml:
	cp phpunit-dist.xml phpunit.xml

test: vendor/composer/installed.json phpunit.xml
	@echo
	-./vendor/bin/phpunit
