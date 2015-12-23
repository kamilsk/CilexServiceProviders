# ConfigServiceProvider

## Supported formats

* PHP
* JSON
* YAML

### Example configuration on PHP

_General_:

```php
return OctoLab\Common\Config\Util\ArrayHelper::merge(
    include __DIR__ . '/parameters.php',
    include __DIR__ . '/component/config.php',
    [
        'component' => [
            'parameter' => 'base component\'s parameter will be overwritten by root config',
            'placeholder_parameter' => '%placeholder%',
            'constant' => E_ALL,
        ],
    ]
);
```

_Parameters_:

```php
return [
    'parameters' => [
        'parameter' => 'will overwrite parameter',
    ],
];
```

### Example configuration on JSON

_General_:

```json
{
  "imports": [
    "parameters.json",
    "component/config.json"
  ],
  "component": {
    "parameter": "base component's parameter",
    "placeholder_parameter": "%placeholder%",
    "constant": "const(E_ALL)"
  }
}
```

_Parameters_:

```json
{
  "parameters": {
    "parameter": "will overwrite parameter"
  }
}
```

### Example configuration on YAML

_General_:

```yml
imports:
  - { resource: parameters.yml }
  - { resource: component/config.yml }

component:
  parameter: "base component's parameter will be overwritten by root config"
  placeholder_parameter: %placeholder%
  constant: const(E_ALL)
```

_Parameters_:

```yml
parameters:
  parameter: "will overwrite parameter"
```

---

All shown configuration examples are equivalent.

> Actually it is not quite so ([issue](https://github.com/kamilsk/Common/issues/22)).

~~~

## Power of YamlConfig

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
