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
