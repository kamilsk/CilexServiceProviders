# MonologServiceProvider

`app/config/monolog/config.yml`

> Пример из документации [MonologServiceProvider](http://silex.sensiolabs.org/doc/providers/monolog.html) Silex.

> Пример из документации [MonologBundle](http://symfony.com/doc/current/reference/configuration/monolog.html) Symfony.

```yaml
monolog:
    name: MyApplicationName
    handlers:
        syslog:
            type:      stream
            path:      /var/log/cilex.log
            level:     ERROR
            bubble:    false
            formatter: error_formatter
```

Теперь доступ к `\Monolog\Handler\AbstractProcessingHandler` можно получить следующим образом:

```php
$syslog = $app['monolog.handlers']['syslog'];
```

Если в приложении добавлены зависимости `symfony/monolog-bridge` и `symfony/event-dispatcher`, то можно получить
доступ к `\Symfony\Bridge\Monolog\Handler\ConsoleHandler`:

```php
$console = $app['monolog.handlers']['console'];
```

Чтобы логи выводились в консоль, необходимо передать слушателю интерфейс вывода:

```php
$outputInterface->setVerbosity(OutputInterface::VERBOSITY_DEBUG);
$app
    ->offsetGet('monolog.handlers')
    ->offsetGet('console')
    ->setOutput($outputInterface)
;
```

или вызвать `\OctoLab\Cilex\Command\Command::setOutputInterface`, передав в метод этот интерфейс.

Если для `handler` указан `formatter`, то его нужно зарегистрировать в приложении до обращения к `$app['monolog']`,
например:

```php
use Monolog\Formatter\JsonFormatter;

$app['error_formatter'] = function ($app) {
    return new JsonFormatter(JsonFormatter::BATCH_MODE_JSON);
};
...
$app['monolog']->error('Some error occurred.');
```
