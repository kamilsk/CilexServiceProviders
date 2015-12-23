# ConfigServiceProvider

## Supported formats

* PHP
* JSON
* YAML

### Example configuration on PHP

```php
$app->register(new ConfigServiceProvider('/path/to/config.php', ['placeholder' => 'placeholder']));
```

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

_Component_:

```php
return [
    'component' => [
        'base_parameter' => 'base parameter will not be overwritten',
        'parameter' => 'base component\'s parameter will be overwritten by component config',
    ],
];
```

### Example configuration on JSON

```php
$app->register(new ConfigServiceProvider('/path/to/config.json', ['placeholder' => 'placeholder']));
```

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

_Component_:

```json
{
  "component": {
    "base_parameter": "base parameter will not be overwritten",
    "parameter": "base component's parameter will be overwritten by component config"
  }
}
```

### Example configuration on YAML

```php
$app->register(new ConfigServiceProvider('/path/to/config.yml', ['placeholder' => 'placeholder']));
```

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

_Component_:

```yml
component:
  base_parameter: "base parameter will not be overwritten"
  parameter: "base component's parameter will be overwritten by component config"
```

---

All shown configuration examples are equivalent.

_Result_:

```php
$app['config'] = [
    'component' => [
        'base_parameter' => 'base parameter will not be overwritten',
        'parameter' => 'base component\'s parameter will be overwritten by root config',
        'placeholder_parameter' => 'placeholder',
        'constant' => E_ALL,
    ],
];
```

> Actually it is not quite so now ([issue](https://github.com/kamilsk/Common/issues/22)).
