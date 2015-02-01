`app/console`:

```php
use Cilex\Application;
use OctoLab\Cilex\Provider\ConfigServiceProvider;
use OctoLab\Cilex\Provider\DoctrineServiceProvider;
use OctoLab\Cilex\Provider\MonologServiceProvider;

$app = new Application('Name');

// регистрируем конфигурацию
$app->register(
    new ConfigServiceProvider(
        'app/config/config.yml',
        ['placeholder' => 'top level parameter']
    )
);
// регистрируем сервисы, которые подхватят настройки из $app['config']
$app->register(new DoctrineServiceProvider());
$app->register(new MonologServiceProvider());

// добавляем команды и инициализируем приложение
$app->command(new ExampleCommand());
...
$app->run();
```
