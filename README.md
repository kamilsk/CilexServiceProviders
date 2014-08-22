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

##### `app/console`:

```php
use Cilex\Application;
use OctoLab\Cilex\Provider\ConfigServiceProvider;
use OctoLab\Cilex\Provider\DoctrineServiceProvider;
use OctoLab\Cilex\Provider\MonologServiceProvider;
use OctoLab\Cilex\Provider\ValidatorServiceProvider;

$app = new Application('ApplicationName');

// регистрируем конфигурацию
$app->register(
    new ConfigServiceProvider(
        'app/config/config.yml',
        ['placeholder' => 'top level parameter']
    )
);
// регистрируем сервисы, которые подхватят настройки из $app['config']
$app->register(new DoctrineServiceProvider());
$app->register(new MonologServiceProvider());
$app->register(new ValidatorServiceProvider());

// добавляем команды и инициализируем приложение
$app->command(new ExampleCommand());
$app->run();
```

##### `app/config/config.yml`:

```yaml
imports:
    - { resource: parameters.yml }
    - { resource: doctrine/config.yml }
    - { resource: monolog/config.yml }
    - { resource: validator/config.yml }

top_level_options:
    top_level_option: top_level_option_value
```

##### `app/config/parameters.yml.dist` -> `app/config/parameters.yml`

```yaml
parameters:
    kernel:
        debug: true
```

##### `app/config/doctrine/config.yml`

> Пример из документации [DoctrineServiceProvider](http://silex.sensiolabs.org/doc/providers/doctrine.html) для Silex.

```yaml
doctrine:
    dbal:
        default_connection: mysql
        connections:
            mysql:
                host:     localhost
                driver:   mysql
                username: user
                password: pass
            sqlite:
                host:     localhost
                driver:   sqlite
                memory:   true
                username: user
                password: pass
```

##### `app/config/monolog/config.yml`

> Пример из документации [MonologBundle](http://symfony.com/doc/current/reference/configuration/monolog.html)

```yaml
monolog:
    handlers:
        syslog:
            type:         stream
            path:         /var/log/cilex.log
            level:        ERROR
            bubble:       false
            formatter:    my_formatter
        main:
            type:         fingers_crossed
            action_level: WARNING
            buffer_size:  30
            handler:      custom
        console:
            type:         console
            verbosity_levels:
                VERBOSITY_NORMAL:       WARNING
                VERBOSITY_VERBOSE:      NOTICE
                VERBOSITY_VERY_VERBOSE: INFO
                VERBOSITY_DEBUG:        DEBUG
```

##### `app/config/validator/config.yml`

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
