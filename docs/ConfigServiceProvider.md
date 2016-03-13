# ConfigServiceProvider

## Supported formats

* JSON
* PHP
* YAML

### Example configuration on JSON

```php
$app->register(new ConfigServiceProvider('/path/to/config.json', ['placeholder' => 'example']));
```

_General_:

```json
{
  "imports": [
    { "resource": "parameters.json" },
    "component/config.json"
  ],
  "app": {
    "placeholder_parameter": "%placeholder%",
    "constant": "const(E_ALL)"
  },
  "component": {
    "parameter": "base component's parameter will be overwritten by root config"
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
    "parameter": "base component's parameter will be overwritten by component config",
    "base_parameter": "base parameter will not be overwritten"
  }
}
```

### Example configuration on PHP

```php
$app->register(new ConfigServiceProvider('/path/to/config.php', ['placeholder' => 'example']));
```

_General_:

```php
return OctoLab\Common\Util\ArrayHelper::merge(
    include __DIR__ . '/parameters.php',
    include __DIR__ . '/component/config.php',
    [
        'app' => [
            'placeholder_parameter' => '%placeholder%',
            'constant' => E_ALL,
        ],
        'component' => [
            'parameter' => 'base component\'s parameter will be overwritten by root config',
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
        'parameter' => 'base component\'s parameter will be overwritten by component config',
        'base_parameter' => 'base parameter will not be overwritten',
    ],
];
```

### Example configuration on YAML

```php
$app->register(new ConfigServiceProvider('/path/to/config.yml', ['placeholder' => 'example']));
```

_General_:

```yml
imports:
  - { resource: parameters.yml }
  - component/config.yml

app:
  placeholder_parameter: %placeholder%
  constant: const(E_ALL)

component:
  parameter: "base component's parameter will be overwritten by root config"
```

_Parameters_:

```yml
parameters:
  parameter: "will overwrite parameter"
```

_Component_:

```yml
component:
  parameter: "base component's parameter will be overwritten by component config"
  base_parameter: "base parameter will not be overwritten"
```

---

All shown configuration examples are equivalent.

_Result_:

```php
$app['config'] = [
    'app' => [
        'placeholder_parameter' => 'example',
        'constant' => E_ALL,
    ],
    'component' => [
        'parameter' => 'base component\'s parameter will be overwritten by root config',
        'base_parameter' => 'base parameter will not be overwritten',
    ],
];
// usage
echo $app['config']['app:placeholder_parameter']; // output "example"
```
