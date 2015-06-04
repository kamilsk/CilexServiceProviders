# ConfigServiceProvider

## Power of [YamlConfig](/src/Config/YamlConfig.php)

```php
$config = (new YamlConfig(new YamlFileLoader(new FileLocator())))
    ->load('app/config/config.yml')
    ->replace(['root_dir' => __DIR__])
    ->toArray()
;
```

`app/config/config.yml`

```yaml
imports:
    - { resource: parameters.yml }
    - { resource: doctrine/config.yml }
    - { resource: monolog/config.yml }

framework:
    base_path: %root_dir%
    parameter: %some_parameter%
```

`app/config/parameters.yml.dist` -> `app/config/parameters.yml`

```yaml
parameters:
    some_parameter: %placeholder%
```
