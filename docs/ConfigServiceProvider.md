# ConfigServiceProvider

## `$app['config']`

## `$app['array_merge_recursive']`

## `$app['array_transform_recursive']`

## Power of YamlFileLoader

`app/config/config.yml`

```yaml
imports:
    - { resource: parameters.yml }
    - { resource: doctrine/config.yml }
    - { resource: monolog/config.yml }

top_level_options:
    top_level_option: %some_parameter%
```

`app/config/parameters.yml.dist` -> `app/config/parameters.yml`

```yaml
parameters:
    some_parameter: %placeholder%
```
