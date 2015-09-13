.PHONY: test

unittest:
	php vendor/bin/phpunit --testsuite unit

inttest:
	php vendor/bin/phpunit --testsuite integration

test:
	php vendor/bin/phpunit
