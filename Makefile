phpcs:
	phpcs --standard=PSR2 --extensions=php --ignore=vendor/* .

test:
	php vendor/bin/phpunit -c phpunit.xml
