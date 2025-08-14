PHP_CONTAINER=docker exec vet_php

linter:
	$(PHP_CONTAINER) vendor/bin/phpcs

linterFix:
	$(PHP_CONTAINER) vendor/bin/phpcbf

phpstan:
	$(PHP_CONTAINER) vendor/bin/phpstan analyse --memory-limit=2G

cacheWarmup:
	$(PHP_CONTAINER) php bin/console cache:warmup		