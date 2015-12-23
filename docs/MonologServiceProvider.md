# MonologServiceProvider

## Configuration example

```yml
monolog:
  handlers:
    access:
      type: stream
      arguments: ["%root_dir%/app/logs/access.log", info, false]
      formatter: { type: json }
    error:
      class: \Monolog\Handler\StreamHandler
      arguments: { stream: "%root_dir%/app/logs/error.log", level: error, bubble: false }
      formatter: { class: \Monolog\Formatter\JsonFormatter }
  processors:
    - { type: memory_peak_usage }
    - { type: uid, arguments: { length: 7 } }
    - { class: \OctoLab\Common\Monolog\Processor\TimeExecutionProcessor }
```

_Handlers_:

Class of instance can be defined by `type` (will convert to `\Monolog\Handler\(CamelCase of type)Handler` or `class`.
Handler constructor arguments are passed as parameter `arguments`.

_Processors_:

Class of instance can be defined by `type` (will convert to `\Monolog\Processor\(CamelCase of type)Processor`
or `class`.

_Formatter_:

Class of instance can be defined by `type` (will convert to `\Monolog\Formatter\(CamelCase of type)Formatter`
or `class`.

## Usage

```php
$app->register(new ConfigServiceProvider('/path/to/config.yml'));

// if you won't to use ConsoleHandler
$app->register(new MonologServiceProvider());

// if you want to use ConsoleHandler
$app->register(new MonologServiceProvider(true));
```

Now access to the `\Monolog\Logger` instance can be obtained as follows:

```php
$logger = $app['monolog'];
```

Access to the `\Monolog\Handler\StreamHandler` instance can be obtained as follows:

```php
$handler = $app['monolog.handlers']['error'];
```

Access to the `\Psr\Log\LoggerInterface` instance can be obtained as follows:

```php
$logger = $app['logger'];
```

If you add a dependency on `symfony/monolog-bridge` and `symfony/event-dispatcher`, you can get
access to the `\Symfony\Bridge\Monolog\Handler\ConsoleHandler` instance:

```php
$console = $app['monolog.handlers']['console'];
```

To display messages in the console, you need to pass output interface to the listener:

```php
$outputInterface->setVerbosity(OutputInterface::VERBOSITY_DEBUG);
$app
    ->offsetGet('monolog.handlers')
    ->offsetGet('console')
    ->setOutput($outputInterface)
;
```

or call `\OctoLab\Cilex\Command\Command::initConsoleHandler($outputInterface)`.
