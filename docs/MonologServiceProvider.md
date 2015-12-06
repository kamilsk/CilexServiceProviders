# MonologServiceProvider

`app/config/monolog/config.yml`

> Example from the Silex [MonologServiceProvider](http://silex.sensiolabs.org/doc/providers/monolog.html) documentation.

> Example from the Symfony [MonologBundle](http://symfony.com/doc/current/reference/configuration/monolog.html) documentation.

```yaml
monolog:
    name: MyApplicationName
    handlers:
        stream:
            type:       stream
            arguments:  ["%root_dir%/app/logs/access.log", info, false]
            formatter:  { type: json }
```

Now access to the `\Monolog\Handler\AbstractProcessingHandler` instance can be obtained as follows:

```php
$syslog = $app['monolog.handlers']['syslog'];
```

If the app added dependencies `symfony/monolog-bridge` and `symfony/event-dispatcher`, you can get
access to the `\Symfony\Bridge\Monolog\Handler\ConsoleHandler` instance:

```php
$console = $app['monolog.handlers']['console'];
```

The logs displayed in the console, you need to pass output interface to the listener:

```php
$outputInterface->setVerbosity(OutputInterface::VERBOSITY_DEBUG);
$app
    ->offsetGet('monolog.handlers')
    ->offsetGet('console')
    ->setOutput($outputInterface)
;
```

or call `\OctoLab\Cilex\Command\Command::initConsoleHandler`, passing in a method this interface.

If for `handler` was specified `formatter`, then it needs to be registered in the application before accessing
to `$app['monolog']`, for example:

```php
use Monolog\Formatter\JsonFormatter;

$app['error_formatter'] = function ($app) {
    return new JsonFormatter(JsonFormatter::BATCH_MODE_JSON);
};
...
$app['monolog']->error('Some error occurred.');
```

## Features

* [ConfigResolver](/src/Monolog/ConfigResolver.php) it is a simple way to configure the `Monolog`:

```yaml
monolog:
  handlers:
    access:
      type: "stream"                                                    # is \Monolog\Handler\StreamHandler
      arguments: ["%root_dir%/app/logs/extended.log", "info", false]
      formatter: { type: "json" }                                       # is \Monolog\Formatter\JsonFormatter
  processors:
    - { type: "memory_peak_usage" }                                     # is \Monolog\Processor\MemoryPeakUsageProcessor
    - { type: "uid", arguments: { length: 7 } }                         # is \Monolog\Processor\UidProcessor
    - { class: OctoLab\Cilex\Monolog\Processor\TimeExecutionProcessor }
```

## Processors

* [TimeExecutionProcessor](/src/Monolog/Processor/TimeExecutionProcessor.php): adds the current execution time
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

* [Dumper](/src/Monolog/Util/Dumper.php)
  * dumpToString: works like `print_r`, but displays the result in single line.
