# DoctrineServiceProvider

`app/config/doctrine/config.yml`

> Example from the Silex documentation [DoctrineServiceProvider](http://silex.sensiolabs.org/doc/providers/doctrine.html).

> Example from the Symfony documentation [DoctrineBundle](http://symfony.com/doc/current/reference/configuration/doctrine.html).

```yaml
doctrine:
    dbal:
        default_connection: mysql
        connections:
            mysql:
                driver:   pdo_mysql
                host:     localhost
                port:     3306
                dbname:   database
                user:     username
                password: pass
            sqlite:
                driver:   pdo_sqlite
                memory:   true
                dbname:   database
                user:     username
                password: pass
```

Now access to the `\Doctrine\DBAL\Connection` instance can be obtained as follows:

```php
$defaultConnection = $app['db'];
$mysql = $app['dbs']['mysql'];
// in this case $defaultConnection === $mysql
$sqlite = $app['dbs']['sqlite'];
```
