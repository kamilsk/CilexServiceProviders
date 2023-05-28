---
code: null
id: MDU6SXNzdWUxMjU4OTExMTg=
databaseId: 125891118
number: 85
url: https://github.com/kamilsk/CilexServiceProviders/issues/85
title: "incorrect behavior"
issueType: null
labels: []
assignees:
  - kamilsk
milestone: "[[milestone-10]]"
parent: null
issueFields: {}
state: CLOSED
stateReason: COMPLETED
duplicateOf: null
createdAt: 2016-01-11T08:01:34Z
updatedAt: 2016-03-12T13:25:42Z
lastEditedAt: null
closedAt: 2016-03-12T13:25:42Z
---

# incorrect behavior

src/Command/Command.php:94

``` php
    public function getDbConnection($alias = null)
    {
        $connection = null;
        if ($alias === null) {
            $connection = $this->getService('db');
        } else {
            $dbs = $this->getService('dbs');
            if ($dbs instanceof \Pimple) {
                $connection = $dbs->offsetGet($alias); <-- throws
            }
        }
        if ($connection) { <-- and this point doesn't work
            return $connection;
        }
        throw new \RuntimeException('DoctrineServiceProvider is not registered.');
    }
```

<!-- 2016-03-12T13:25Z https://github.com/kamilsk/CilexServiceProviders/issues/85#issuecomment-195742285
there is in a signature of the function `@throws \InvalidArgumentException if the identifier (alias) is not defined`
-->
