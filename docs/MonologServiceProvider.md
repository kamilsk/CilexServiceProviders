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

## Processors

* [TimeExecutionProcessor](src/Monolog/Processor/TimeExecutionProcessor.php): adds the current execution time
(in seconds accurate to the nearest millisecond) to a log record.

__sprintf vs number_format performance__

TimeExecutionProcessor uses `sprintf` to format execution time. `number_format` as an alternative,
but a simple test shows a slight advantage of `sprintf`:

```php
$iterations = 500000;
$float = 123.456789;
$t1 = microtime(true);
for ($i = 0; $i < $iterations; $i++) {
    sprintf('%01.3f', $float);
}
$t2 = microtime(true);
echo 'sprintf:       ', $t2 - $t1, PHP_EOL;
$t1 = microtime(true);
for ($i = 0; $i < $iterations; $i++) {
    number_format($float, 3, '.', '');
}
$t2 = microtime(true);
echo 'number_format: ', $t2 - $t1, PHP_EOL;
```

Will output:

```
sprintf:       0.81137895584106 # ~0.8
number_format: 0.92237401008606 # ~0.9
```

## Utils

* [Dumper](src/Monolog/Util/Dumper.php)
  * dumpToString: works like `print_r`, but displays the result in single line.
