# Application and commands

## Application

`Application::register($provider, array $values = [])` prevents register the same Service Provider twice.

## Commands

### Basic

Useful shortcut methods:

- `Command\Command::getContainer()` return `Pimple`
- `Command\Command::getService($name)` return registered service
- `Command\Command::getConfig($path)` return application configuration (`$app['config']` or `$app['config'][$path]`)
- `Command\Command::getDbConnection($id)` return DBAL connection (`$app['connection']` or `$app['connections'][$id]`)
- `Command\Command::getLogger($id)` return `Psr\Log\LoggerInterface` (`$app['logger']` or `$app['loggers'][$id]`)

#### Usage

`Command\Command::getConfig($path = null, $default = null)`

```php
// $app['config'] === ['path' => ['to' => ['config' => 'value']]]

if ($command->getConfig('path:to:config', 'default') === 'value') {
    echo 'Path works correctly.';
} else {
    echo 'Value "default" was used.';
}

// will output "Path works correctly."
```

`Command\Command::getDbConnection($alias = null)`

```yml
doctrine:
  dbal:
    connections:
      mysql:
        driver:   pdo_mysql
        host:     localhost
        port:     3306
        dbname:   database
        user: user
        password: pass
      sqlite:
        driver:   pdo_sqlite
        memory:   true
        dbname:   database
        user: user
        password: pass
    default_connection: mysql
    types:
      enum: string
```

```php
if ($command->getDbConnection('mysql') === $app['connections']['mysql']) {
    echo 'Alias works correctly.';
} else {
    throw new Exception();
}

// will output "Alias works correctly."
```

`Command\Command::getLogger($channel = null)`

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

```php
if ($command->getLogger('debug') === $app['loggers']['debug']) {
    echo 'Alias works correctly.';
} else {
    throw new Exception();
}

// will output "Alias works correctly."
```

### PresetCommand

#### Configuration example

```yml
cli_menu:
  title: CLI Menu
  items:
  - { text: "Hello, World", callable: "test:hello", arguments: { message: World } }
  - { text: Fibonacci sequence, callable: "test:fibonacci", options: { size: 10 } }
```

##### New in version 3.1

```yml
cli_menu:
  title: CLI Menu
  items:
  - { text: "Hello, World", callable: "test:hello", arguments: { message: World } }
  - { text: Fibonacci sequence, callable: "test:fibonacci", options: { size: 10 } }
  - text: Fibonacci sequences
    commands:
    - { name: "test:fibonacci", options: { size: 1 } }
    - { name: "test:fibonacci", options: { size: 2 } }
    - { name: "test:fibonacci", options: { size: 3 } }
    - { name: "test:fibonacci", options: { size: 5 } }
```

#### Usage

```php
$app->command(new HelloCommand('example'));
$app->command(new FibonacciCommand('example'));
$app->command(new PresetCommand('example'));
$app->run();
```

```bash
$ app/console example:menu
```

Displays menu of three items:

- [Hello, World](/tests/Command/CliMenu/HelloCommand.php)
- [Fibonacci sequence](/tests/Command/CliMenu/FibonacciCommand.php)
- Exit

You can select "Hello, World" to run `$ app/console example:hello -m World`
or "Fibonacci sequence" to execute `$ app/console example:fibonacci --size=10`

```bash
$ app/console example:menu --dump
```

Will output:

```
Total commands: 3
 - example:hello World

 - example:fibonacci --size=10

 - example:fibonacci --size=1
 - example:fibonacci --size=2
 - example:fibonacci --size=3
 - example:fibonacci --size=5
```

### Doctrine\CheckMigrationCommand

Displays contents of FileBasedMigration.

#### Usage

```php
$app->command(new CheckMigrationCommand('example'));
$app->run();
```

```bash
$ app/console example:check /path/to/migration.sql
# or
$ app/console example:check 20151202142239
# or
$ app/console example:check Your\Migration\Version
```

Displays contents of these migrations.

```
Migration /path/to/migration.sql contains
1. ...
2. ...
...
```

Or

```
Upgrade for migration (20151202142239|Your\Migration\Version)
1. ...
2. ...
...
Downgrade for migration (20151202142239|Your\Migration\Version)
1. ...
...
```

Where `20151202142239` (as example) is `Doctrine Migration` (`OctoLab\Common\Doctrine\Migration\FileBasedMigration`).

### Doctrine\GenerateIndexNameCommand

The command generates and displays name of index (unique, foreign or usual).

#### Usage

```php
$app->command(new GenerateIndexNameCommand('example'));
$app->run();
```

```bash
$ app/console example:generate-index-name --type=uniq -t table_name -c user_id,title

// will output "Index name: UNIQ_14F53ECDA76ED3952B36786B"
```
