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
$ composer require kamilsk/cilex-service-providers:~0.1
$ composer update
```

## Рекомендации

### Связка `config.yml + parameters.yml.dist`

...

### Пример использования

`app/console`:

```php
use Cilex\Application;
use OctoLab\Cilex\Provider\ConfigServiceProvider;
use OctoLab\Cilex\Provider\DoctrineServiceProvider;
use OctoLab\Cilex\Provider\MonologServiceProvider;
use OctoLab\Cilex\Provider\ValidatorServiceProvider;

$app = new Application('ApplicationName');
// регистрируем конфигурацию
$app->register(new ConfigServiceProvider('app/config/config.yml', ['placeholder' => 'top level parameter']));
// регистрируем Doctrine DBAL и Monolog, которые подхватят настройки из $app['config']
$app->register(new DoctrineServiceProvider());
$app->register(new MonologServiceProvider());

// добавляем команды и инициализируем приложение
$app->command();
$app->run();
```

`app/config/config.yml`:

```yaml
imports:
    - { resource: parameters.yml }
    - { resource: doctrine/config.yml }
    - { resource: monolog/config.yml }
    - { resource: validator/config.yml }
```

`app/config/parameters.yml.dist` -> `app/config/parameters.yml`

```yaml
...
```

`app/config/doctrine/config.yml`

```yaml
...
```

`app/config/monolog/config.yml`

```yaml
...
```

`app/config/validator/config.yml`

```yml
...
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
