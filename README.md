composer install
./bin/console make:command HelloWorld
./bin/console make:unit-test \Command\HelloWorldCommand

rm -fr bin/.phpunit
rm -fr vendor

https://symfony.com/doc/current/doctrine.html