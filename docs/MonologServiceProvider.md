# MonologServiceProvider

## Configuration example

```yml
monolog:
  channels:
    app:
      handlers:
      - file
    debug:
      arguments: { name: dev }
      handlers:
      - chrome
      - desktop
    db:
      name: app
      handlers:
      - file
      processors:
      - memory
      - time
  default_channel: app
  handlers:
    file:
      type: stream
      arguments: ["%root_dir%/app/logs/info.log", info, true]
    stream:
      type: stream
      arguments: ["%root_dir%/app/logs/debug.log", debug]
      formatter: json
    chrome:
      type: chrome_php
      arguments: { level: info, bubble: true }
      formatter: chrome
    desktop:
      class: OctoLab\Common\Monolog\Handler\DesktopNotificationHandler
      arguments: ["Desktop Notification"]
  processors:
    memory:
      type: memory_usage
    time:
      class: OctoLab\Common\Monolog\Processor\TimeExecutionProcessor
  formatters:
    json:
      type: json
    chrome:
      type: chrome_php
```

## Usage

```php
$app->register(new ConfigServiceProvider('/path/to/config.yml'));
$app->register(new MonologServiceProvider());
```

Now access to the `Monolog\Logger` instance can be obtained as follows:

```php
$logger = $app['logger'];
// or
$logger = $app['loggers']->getDefaultChannel();
// or
$logger = $app['loggers'][$app['config']['monolog:default_channel']];
```
