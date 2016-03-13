# Application and commands

## Application

`Application::register($provider, array $values = [])` prevents register the same Service Provider twice.

## Commands

### Basic

Useful shortcut methods:

- `Command\Command::getContainer()` return `Pimple` (e.g. `Cilex\Application`)
- `Command\Command::getService($name)` return registered service
- `Command\Command::getConfig()` return application configuration (`$app['config']`)
- `Command\Command::getDbConnection()` return default connection (`$app['db']`)
- `Command\Command::getLogger()` return `Psr\Log\LoggerInterface` (`$app['logger']`)

__New features in version 2.1:__

- `Command\Command::getConfig($path = null, $default = null)`

```php
// $app['config'] === ['path' => ['to' => ['config' => 'value']]]

if ($command->getConfig('path:to:config', 'default') === 'value') {
    echo 'Path works correctly.';
} else {
    echo 'Value "default" was used.';
}

// will output
// Path works correctly.
```

- `Command\Command::getDbConnection($alias = null)`

```yml
doctrine:
  dbal:
    connections:
      mysql:
        driver:   pdo_mysql
        host:     localhost
        port:     3306
        dbname:   database
        username: user
        password: pass
      sqlite:
        driver:   pdo_sqlite
        memory:   true
        dbname:   database
        username: user
        password: pass
```

```php
if ($command->getDbConnection('mysql') === $app['dbs']['mysql']) {
    echo 'Alias works correctly.';
} else {
    throw new Exception();
}

// will output
// Alias works correctly.
```

- `Command\Command::getLogger($channel = null)`

```yml
monolog:
  default_channel: logger
  channels:
    db:
      name: logger
      handlers:
      - info
    service:
      id: logger
      handlers:
      - type: chrome_php
        arguments: [info, true]
      - id: info
        formatter: { type: json }
        processors:
        - { type: uid, arguments: { length: 7 } }
  handlers:
    info:
      type: stream
      arguments: ["%root_dir%/app/logs/info.log", info, true]
      formatter: { type: normalizer }
```

```php
if ($command->getLogger('db') === $app['monolog.resolver']->getChannels()['db']) {
    echo 'Logger ID works correctly.';
} else {
    throw new Exception();
}

// will output
// Logger ID works correctly.
```

### PresetCommand

#### Configuration example

```yml
cli_menu:
  title: CLI Menu
  items:
  - { text: "Hello, World", callable: example:hello, options: { message: World } }
  - { text: Fibonacci sequence, callable: example:fibonacci, options: { size: 10 } }
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

- [Hello, World](/tests/Command/Mock/HelloCommand.php)
- [Fibonacci sequence](/tests/Command/Mock/FibonacciCommand.php)
- Exit

You can select "Hello, World" to run `$ app/console example:hello -m World`
or "Fibonacci sequence" to execute `$ app/console example:fibonacci --size=10`

### Doctrine\CheckMigrationCommand

__Usage:__

```php
$app->command(new CheckMigrationCommand('example'));
$app->run();
```

```bash
$ app/console example:check /path/to/migration.sql
# or
$ app/console example:check 20151202142239
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
Upgrade for migration 20151202142239
1. ...
2. ...
...
Downgrade for migration 20151202142239
1. ...
...
```

Where `20151202142239` (as example) is `Doctrine Migration` (`OctoLab\Common\Doctrine\Migration\FileBasedMigration`).

### Doctrine\GenerateIndexNameCommand

__Usage:__

```php
$app->command(new GenerateIndexNameCommand('example'));
$app->run();
```

```bash
$ app/console example:generate-index-name --type=uniq -t table_name -c user_id,title
```

Generates and displays name of unique index for table `table_name` and columns `(user_id, title)`.
