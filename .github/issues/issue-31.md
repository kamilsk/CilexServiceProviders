---
code: null
id: MDU6SXNzdWU1NDc5NTgzMw==
databaseId: 54795833
number: 31
url: https://github.com/kamilsk/CilexServiceProviders/issues/31
title: "helper methods in base command"
issueType: null
labels: []
assignees:
  - kamilsk
milestone: "[[milestone-3]]"
parent: null
issueFields: {}
state: CLOSED
stateReason: COMPLETED
duplicateOf: null
createdAt: 2015-01-19T18:14:37Z
updatedAt: 2015-02-01T08:41:28Z
lastEditedAt: null
closedAt: 2015-02-01T08:41:28Z
---

# helper methods in base command

it will be useful in `\OctoLab\Cilex\Command\Command`:

``` php
    /**
     * @return \Doctrine\DBAL\Connection
     */
    public function getDbConnection()
    {
        return $this->getService('db');
    }

    /**
     * @return \Monolog\Logger
     */
    public function getLogger()
    {
        return $this->getService('monolog');
    }
```
