.PHONY: test unittest prepint inttest

unittest:
	php vendor/bin/phpunit --testsuite unit

prepaccept:
	rm -rf test/acceptance/app/tmp/*

accept: prepaccept
	php vendor/bin/phpunit --testsuite integration

test: prepaccept
	php vendor/bin/phpunit
