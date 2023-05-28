---
code: null
id: MDU6SXNzdWUxMjQwNDYyNjk=
databaseId: 124046269
number: 79
url: https://github.com/kamilsk/CilexServiceProviders/issues/79
title: "new getDbConnection functionality"
issueType: null
labels: []
assignees:
  - kamilsk
milestone: "[[milestone-8]]"
parent: null
issueFields: {}
state: CLOSED
stateReason: COMPLETED
duplicateOf: null
createdAt: 2015-12-28T10:07:05Z
updatedAt: 2016-01-04T07:09:26Z
lastEditedAt: null
closedAt: 2016-01-04T07:09:26Z
---

# new getDbConnection functionality

`Command::getDbConnection('alias')`

<!-- 2015-12-28T11:40Z https://github.com/kamilsk/CilexServiceProviders/issues/79#issuecomment-167550148
draw:

``` php
    public function getDbConnection($alias = null)
    {
        if ($alias) {
            /** @var \Pimple $connections */
            $connections = $this->getService('dbs');
            if ($connections->offsetExists($alias)) {
                return $connections->offsetGet($alias);
            }
            throw new \RuntimeException('Database connection is not exists.');
        }
        return parent::getDbConnection();
    }
```
-->
