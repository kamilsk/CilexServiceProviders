# DoctrineServiceProvider

## Configuration example

```yml
doctrine:
  dbal:
    default_connection: mysql
    connections:
      mysql:
        driver:   pdo_mysql
        host:     localhost
        port:     3306
        dbname:   database
        username: user
        password: pass
      sqlite:
        driver:   pdo_sqlite
        memory:   true
        dbname:   database
        username: user
        password: pass
    types:
      enum: string
      custom: Your\Custom\Type # extends Doctrine\DBAL\Types\Type
```

## Usage

```php
$app->register(new ConfigServiceProvider('/path/to/config.yml'));

// if you won't to use ConnectionHelper ($app->offsetGet('console')->getHelperSet()->get('connection')->getConnection())
$app->register(new DoctrineServiceProvider());

// if you want to use ConnectionHelper with default connection
$app->register(new DoctrineServiceProvider(true));

// if you want to use ConnectionHelper with specified connection
$app->register(new DoctrineServiceProvider('sqlite'));
```

Now access to the `\Doctrine\DBAL\Connection` instance can be obtained as follows:

```php
$default = $app['db'];
$mysql = $app['dbs']['mysql'];
// in this case $default === $mysql
$sqlite = $app['dbs']['sqlite'];
```
