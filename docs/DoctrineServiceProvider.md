# DoctrineServiceProvider

## Configuration example

```yml
doctrine:
  dbal:
    connections:
      mysql:
        driver:   pdo_mysql
        host:     localhost
        port:     3306
        dbname:   database
        user:     user
        password: pass
      sqlite:
        driver:   pdo_sqlite
        memory:   true
        dbname:   database
        user:     user
        password: pass
    default_connection: mysql
    types:
      enum: string
      custom: Your\Custom\Type # extends Doctrine\DBAL\Types\Type
```

## Usage

```php
$app->register(new ConfigServiceProvider('/path/to/config.yml'));
$app->register(new DoctrineServiceProvider());
```

Now access to the `Doctrine\DBAL\Connection` instance can be obtained as follows:

```php
$default = $app['connection'];
$mysql = $app['connections']['mysql'];
// in this case $default === $mysql
$sqlite = $app['connections']['sqlite'];
```
