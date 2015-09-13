.PHONY: test unittest prepint inttest

unittest:
	php vendor/bin/phpunit --testsuite unit

prepint:
	rm -rf test/integration/app/tmp/*

inttest: prepint
	php vendor/bin/phpunit --testsuite integration

test: prepint
	php vendor/bin/phpunit
