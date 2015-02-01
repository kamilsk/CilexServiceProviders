`app/config/doctrine/config.yml`

> Пример из документации [DoctrineServiceProvider](http://silex.sensiolabs.org/doc/providers/doctrine.html) Silex.

> Пример из документации [DoctrineBundle](http://symfony.com/doc/current/reference/configuration/doctrine.html) Symfony.

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
                username: user
                password: pass
            sqlite:
                driver:   pdo_sqlite
                memory:   true
                dbname:   database
                username: user
                password: pass
```

Теперь доступ к `\Doctrine\DBAL\Connection` можно получить следующим образом:

```php
$defaultConnection = $app['db'];
$mysql = $app['dbs']['mysql'];
// в данном случае $defaultConnection === $mysql
$sqlite = $app['dbs']['sqlite'];
```
