> # Cilex Service Providers
>
> Набор переработанных провайдеров для Cilex.

## Установка

### Git

```bash
$ git clone git@github.com:kamilsk/CilexServiceProviders.git
$ cd CilexServiceProvider && composer install
```

### Composer

```bash
$ composer require kamilsk/cilex-service-providers:*
$ composer update
```

## Тестирование

```bash
$ vendor/bin/phpunit
$ # или индивидуальные тест-кейсы
$ vendor/bin/phpunit --testsuite config
$ vendor/bin/phpunit --testsuite doctrine
$ vendor/bin/phpunit --testsuite monolog
$ vendor/bin/phpunit --testsuite validator
```
