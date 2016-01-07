# Usage example

```php
use OctoLab\Cilex\Application;
use OctoLab\Cilex\Command\PresetCommand;
use OctoLab\Cilex\Command\Doctrine\CheckMigrationCommand;
use OctoLab\Cilex\Command\Doctrine\GenerateIndexNameCommand;
use OctoLab\Cilex\Provider\ConfigServiceProvider;
use OctoLab\Cilex\Provider\DoctrineServiceProvider;
use OctoLab\Cilex\Provider\MonologServiceProvider;


require __DIR__ . '/vendor/autoload.php';

$app = new Application('Example');

// register configuration
$app->register(
    new ConfigServiceProvider(
        '/path/to/config.yml',
        ['placeholder' => 'top level parameter']
    )
);

// registered services that will pick up settings from $app['config']
$app->register(new DoctrineServiceProvider('pgsql'));
$app->register(new MonologServiceProvider(true));

// add commands and initialize the app
$app->command(new PresetCommand('example'));
$app->command(new CheckMigrationCommand('migration'));
$app->command(new GenerateIndexNameCommand('doctrine'));
...
$app->run();
```

Run application:

```bash
$ php console.php
```

## Managing your ignored parameters with Composer

`config.yml + parameters.yml.dist`

Use `config.yml` to store settings, independent from the environment, and `parameters.yml` to override them
depending on your specific environment.

_1._ Add [ParameterHandler](https://github.com/Incenteev/ParameterHandler) dependency:

```bash
$ composer require incenteev/composer-parameter-handler:~2.0
```

_2._ Set `composer.json`:

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
    "file": "/path/to/parameters.yml"
  }
}
```

_3._ Create a file `/path/to/parameters.yml.dist` and specify a necessary parameters:

```yml
parameters:
  some_parameter: some_value
```

_4._ Exclude `/path/to/parameters.yml` from vcs (e.g. git):

```bash
$ echo '/path/to/parameters.yml' >> .gitignore
```

_5._ Use these settings in `config.yml`:

```yml
imports:
  - { resource: relative/path/to/parameters.yml }

component:
  component_option: %some_parameter%
```

_6._ Update your project:

```bash
$ composer update
```
