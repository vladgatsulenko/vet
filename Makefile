PHP_CONTAINER=docker exec vet_php

linter:
	$(PHP_CONTAINER) vendor/bin/phpcs

linterFix:
	$(PHP_CONTAINER) vendor/bin/phpcbf
