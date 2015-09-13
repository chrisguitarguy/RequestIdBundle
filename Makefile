.PHONY: test unittest prepint inttest

unittest:
	php vendor/bin/phpunit --testsuite unit

prepaccept:
	rm -rf test/integration/app/tmp/*

accept: prepint
	php vendor/bin/phpunit --testsuite integration

test: prepint
	php vendor/bin/phpunit
