# Usage example

`app/console`:

```php
use Cilex\Application;
use OctoLab\Cilex\Provider\ConfigServiceProvider;
use OctoLab\Cilex\Provider\DoctrineServiceProvider;
use OctoLab\Cilex\Provider\MonologServiceProvider;

$app = new Application('Name');

// register configuration
$app->register(
    new ConfigServiceProvider(
        'app/config/config.yml',
        ['placeholder' => 'top level parameter']
    )
);
// registered services that will pick up settings from $app['config']
$app->register(new DoctrineServiceProvider());
$app->register(new MonologServiceProvider());

// add commands and initialize the app
$app->command(new ExampleCommand());
...
$app->run();
```

## Managing your ignored parameters with Composer

`config.yml + parameters.yml.dist`

Use `config.yml` to store settings, independent from the environment, and `parameters.yml` to override them
depending on your specific environment.

1) Add [ParameterHandler](https://github.com/Incenteev/ParameterHandler) dependency:
```bash
$ composer require incenteev/composer-parameter-handler:~2.0
```
2) Set `composer.json`:
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
        "file": "path/to/parameters.yml"
    }
}
```
3) Create a file `path/to/parameters.yml.dist` and specify a necessary parameters:
```yaml
parameters:
    some_parameter: some_value
```
4) Exclude `path/to/parameters.yml` from vcs (e.g. git):
```bash
$ echo 'path/to/parameters.yml' >> .gitignore
```
5) Use these settings in `config.yml`:
```yaml
component:
    component_option: %some_parameter%
```
6) Update your project:
```bash
$ composer update
```

## Useful features of OctoLab\Cilex\Command\Command

* Command namespace
* Command::getDbConnection
* Command::getLogger
* Command::initConsoleHandler