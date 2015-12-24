# Application and commands

## Application

`\OctoLab\Cilex\Application::register()` prevents register the same Service Provider twice.

## Commands

### Basic

Useful shortcut methods:

- `\OctoLab\Cilex\Command\Command::getContainer()` return `\Pimple` (e.g. `\Cilex\Application`)
- `\OctoLab\Cilex\Command\Command::getService($name)` return registered service
- `\OctoLab\Cilex\Command\Command::getConfig()` return application configuration (`$app['config']`)
- `\OctoLab\Cilex\Command\Command::getDbConnection()` return default connection (`$app['db']`)
- `\OctoLab\Cilex\Command\Command::getLogger()` return `\Psr\Log\LoggerInterface` (`$app['logger']`)

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
$ ./console example:menu
```

Displays menu of three items:

- [Hello, World](/tests/Command/Mock/HelloCommand.php)
- [Fibonacci sequence](/tests/Command/Mock/FibonacciCommand.php)
- Exit

You can select "Hello, World" to run `$ ./console example:hello -m World`
or "Fibonacci sequence" to execute `$ ./console example:fibonacci --size=10`

### Doctrine/CheckMigrationCommand

__Usage:__

```php
$app->command(new CheckMigrationCommand('example'));
$app->run();
```

```bash
$ ./console example:check /path/to/migration.sql
# or
$ ./console example:check 20151202142239
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

Where `20151202142239` (as example) is `Doctrine Migration` (`\OctoLab\Common\Doctrine\Migration\FileBasedMigration`).

### Doctrine/GenerateIndexNameCommand

__Usage:__

```php
$app->command(new GenerateIndexNameCommand('example'));
$app->run();
```

```bash
$ ./console example:generate-index-name --type=uniq -t table_name -c user_id,title
```

Generates and displays name of unique index for table "table_name" and columns "(user_id, title)".
