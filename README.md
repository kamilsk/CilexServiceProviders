> # Cilex Service Providers
>
> Набор переработанных провайдеров для Cilex.

[![SensioLabsInsight](https://insight.sensiolabs.com/projects/fde7b27e-7f5c-452e-a289-5614f83246c9/big.png)](https://insight.sensiolabs.com/projects/fde7b27e-7f5c-452e-a289-5614f83246c9)

## Установка

### Git

```bash
$ git clone git@github.com:kamilsk/CilexServiceProviders.git
$ cd CilexServiceProvider && composer install
```

### Composer

```bash
$ composer require kamilsk/cilex-service-providers:~1.0
$ composer update
```

## Рекомендации

### Связка `config.yml + parameters.yml.dist`

Используйте `config.yml` для хранения настроек, не зависящих от окружения, и `parameters.yml` для их переопределения
в зависимости от конкретного окружения.

1) Добавьте зависимость от [ParameterHandler](https://github.com/Incenteev/ParameterHandler):
```bash
$ composer require incenteev/composer-parameter-handler:~2.0
```
2) Настройте `composer.json`:
```json
"scripts": {
    "post-install-cmd": [
        "Incenteev\\ParameterHandler\\ScriptHandler::buildParameters"
    ],
    "post-update-cmd": [
        "Incenteev\\ParameterHandler\\ScriptHandler::buildParameters"
    ]
},
"extra": {
    "incenteev-parameters": {
        "file": "path/to/parameters.yml"
    }
}
```
3) Создайте файл `path/to/parameters.yml.dist` и пропишите там необходимые параметры:
```yaml
parameters:
    some_parameter: some_value
```
4) Исключите `path/to/parameters.yml` из vcs (например, git):
```bash
$ echo 'path/to/parameters.yml' >> .gitignore
```
5) Используйте эти параметры в `config.yml`:
```yaml
component:
    component_option: %some_parameter%
```
6) Обновите проект:
```bash
$ composer update
```

### Пример использования

##### `app/console`:

```php
use Cilex\Application;
use OctoLab\Cilex\Provider\ConfigServiceProvider;
use OctoLab\Cilex\Provider\DoctrineServiceProvider;
use OctoLab\Cilex\Provider\MonologServiceProvider;

$app = new Application('Name');

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

// добавляем команды и инициализируем приложение
$app->command(new ExampleCommand());
...
$app->run();
```

##### `app/config/config.yml`:

```yaml
imports:
    - { resource: parameters.yml }
    - { resource: doctrine/config.yml }
    - { resource: monolog/config.yml }

top_level_options:
    top_level_option: %some_parameter%
```

##### `app/config/parameters.yml.dist` -> `app/config/parameters.yml`

```yaml
parameters:
    some_parameter: %placeholder%
```

##### `app/config/doctrine/config.yml`

> Пример из документации [DoctrineServiceProvider](http://silex.sensiolabs.org/doc/providers/doctrine.html) Silex.

> Пример из документации [DoctrineBundle](http://symfony.com/doc/current/reference/configuration/doctrine.html) Symfony.

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

> Пример из документации [MonologServiceProvider](http://silex.sensiolabs.org/doc/providers/monolog.html) Silex.

> Пример из документации [MonologBundle](http://symfony.com/doc/current/reference/configuration/monolog.html) Symfony.

```yaml
monolog:
    handlers:
        syslog:
            type:      stream
            path:      /var/log/cilex.log
            level:     ERROR
            bubble:    false
            formatter: error_formatter
```

Если для handler указан `formatter`, то его нужно зарегистрировать в приложении до обращения к `$app['monolog']`, например:

```php
use Monolog\Formatter\JsonFormatter;

$app['error_formatter.batch_mode'] = JsonFormatter::BATCH_MODE_JSON;
$app['error_formatter'] = function ($app) {
    return new JsonFormatter($app['error_formatter.batch_mode']);
};
...
$app['monolog']->addError('Some error occurred.');
```

## Тестирование

```bash
$ vendor/bin/phpunit
$ # или индивидуальные тест-кейсы
$ vendor/bin/phpunit --testsuite config
$ vendor/bin/phpunit --testsuite doctrine
$ vendor/bin/phpunit --testsuite monolog
```
