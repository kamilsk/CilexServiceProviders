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
      class: Monolog\Handler\StreamHandler
      arguments: { stream: "%root_dir%/app/logs/error.log", level: error, bubble: false }
      formatter: { class: Monolog\Formatter\JsonFormatter }
  processors:
    - { type: memory_peak_usage }
    - { type: uid, arguments: { length: 7 } }
    - { class: OctoLab\Common\Monolog\Processor\TimeExecutionProcessor }
```

_Handlers_:

Class of instance can be defined by `type` (will convert to `Monolog\Handler\(CamelCase of type)Handler` or `class`.

_Processors_:

Class of instance can be defined by `type` (will convert to `Monolog\Processor\(CamelCase of type)Processor`
or `class`.

_Formatter_:

Class of instance can be defined by `type` (will convert to `Monolog\Formatter\(CamelCase of type)Formatter`
or `class`.

__New features in version 2.1:__

```yml
monolog:
  default_channel: logger
  channels:
    db:
      name: logger
      handlers:
      - info
      processors:
      - memory
    service:
      id: logger
      handlers:
      - type: chrome_php
        arguments: [info, true]
        formatter: chrome
      - id: info
        formatter: { type: json }
        processors:
        - { type: uid, arguments: { length: 7 } }
  handlers:
    info:
      type: stream
      arguments: ["%root_dir%/app/logs/info.log", info, true]
      formatter: { type: normalizer }
  processors:
    memory:
      type: memory_peak_usage
  formatters:
    chrome:
      type: chrome_php
```

---

Arguments of the class constructor are taken from the parameter `arguments`.

## Usage

```php
$app->register(new ConfigServiceProvider('/path/to/config.yml'));

// if you won't to use ConsoleHandler
$app->register(new MonologServiceProvider());

// if you want to use ConsoleHandler
$app->register(new MonologServiceProvider(true));

// new feature in version 2.1: if you want to use ConsoleHandler with specific ID
$app->register(new MonologServiceProvider('console'));
```

Now access to the `Monolog\Logger` instance can be obtained as follows:

```php
$logger = $app['monolog'];

// new feature in version 2.1:
$logger = $app['monolog.resolver']->getChannels()['db'];
// or OctoLab\Cilex\Command\Command::getLogger('db')
```

Access to the `Monolog\Handler\StreamHandler` instance:

```php
// removed in 2.1
// $handler = $app['monolog.handlers']['error'];
// use example below
$handler = $app['monolog.resolver']->getHandlers()['error'];
```

Access to the `Psr\Log\LoggerInterface` instance:

```php
$logger = $app['logger'];
```

If you add a dependency on `symfony/monolog-bridge` and `symfony/event-dispatcher`, you can get
access to the `Symfony\Bridge\Monolog\Handler\ConsoleHandler` instance:

```php
// removed in 2.1
// $console = $app['monolog.handlers']['console'];
// use example below
$handler = $app['monolog.resolver']->getHandlers()['console'];
```

To display messages in the console, you need to pass output interface to the listener:

```php
$app['monolog.resolver']->getHandlers()['console']->setOutput($outputInterface);
```

or call `OctoLab\Cilex\Command\Command::initConsoleHandler($outputInterface)`.
