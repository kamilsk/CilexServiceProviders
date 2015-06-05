# ConfigServiceProvider

## Power of [YamlConfig](/src/Config/YamlConfig.php)

```php
$config = (new YamlConfig(new YamlFileLoader(new FileLocator())))
    ->load('app/config/config.yml')
    ->replace(['root_dir' => __DIR__, 'placeholder' => 'value'])
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
    error_reporting: const(E_ALL)
```

`app/config/parameters.yml.dist` -> `app/config/parameters.yml`

```yaml
parameters:
    some_parameter: %placeholder%
```

## Custom YAML parser

[SymfonyYamlParser](/src/Config/Parser/SymfonyYamlParser.php) as default parser, based on `symfony/yaml`.

As alternative you can use [DipperYamlParser](/src/Config/Parser/DipperYamlParser.php), based on `secondparty/dipper`.

Or you can define your own parser, just implement simple [Parser](/src/Config/Parser/ParserInterface.php) interface.
