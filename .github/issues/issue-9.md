---
code: null
id: MDU6SXNzdWU0MTM2Mzk2NQ==
databaseId: 41363965
number: 9
url: https://github.com/kamilsk/CilexServiceProviders/issues/9
title: "[master] DoctrineServiceProvider сделать провязку для миграций"
issueType: null
labels: []
assignees:
  - kamilsk
milestone: "[[milestone-1]]"
parent: null
issueFields: {}
state: CLOSED
stateReason: COMPLETED
duplicateOf: null
createdAt: 2014-08-28T07:56:29Z
updatedAt: 2014-08-28T08:22:30Z
lastEditedAt: null
closedAt: 2014-08-28T08:22:30Z
---

# [master] DoctrineServiceProvider сделать провязку для миграций

У компонента `doctrine/migrations` есть внутренняя зависимость от `ConnectionHelper`:

``` php
$app
    ->offsetGet('console')
    ->getHelperSet()
    ->set(new \Doctrine\DBAL\Tools\Console\Helper\ConnectionHelper($app->offsetGet('db')), 'connection')
;
```

Нужно реализовать провязку внутри провайдера, при передаче флага $hasMigrations.

<!-- 2014-08-28T07:57Z https://github.com/kamilsk/CilexServiceProviders/issues/9#issuecomment-53684746
добавить в suggest нужную версию миграций `doctrine/migrations`
-->
