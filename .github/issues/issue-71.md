---
code: null
id: MDU6SXNzdWUxMjEwMTcxMjY=
databaseId: 121017126
number: 71
url: https://github.com/kamilsk/CilexServiceProviders/issues/71
title: "add opportunity to define type for doctrine"
issueType: null
labels: []
assignees:
  - kamilsk
milestone: "[[milestone-4]]"
parent: null
issueFields: {}
state: CLOSED
stateReason: COMPLETED
duplicateOf: null
createdAt: 2015-12-08T14:25:30Z
updatedAt: 2015-12-20T17:20:55Z
lastEditedAt: null
closedAt: 2015-12-20T17:20:55Z
---

# add opportunity to define type for doctrine

ugly hack

``` php
/*
Migration 20150114133010 failed during Pre-Checks. Error Unknown database type enum requested,
Doctrine\DBAL\Platforms\MySqlPlatform may not support it.

[Doctrine\DBAL\DBALException]
Unknown database type enum requested, Doctrine\DBAL\Platforms\MySqlPlatform may not support it.
 */
$app['db']->getDatabasePlatform()->registerDoctrineTypeMapping('enum', 'string');
```
