---
code: null
id: MDU6SXNzdWU0MDgzNTQ2Nw==
databaseId: 40835467
number: 5
url: https://github.com/kamilsk/CilexServiceProviders/issues/5
title: "[master] ConfigServiceProvider поддержка вложенности"
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
createdAt: 2014-08-21T18:46:06Z
updatedAt: 2014-08-29T17:41:14Z
lastEditedAt: null
closedAt: 2014-08-29T17:41:14Z
---

# [master] ConfigServiceProvider поддержка вложенности

Необходимо реализовать поддержку вложенной структуры, т.е.

``` yaml
parameters:
    kernel.debug: true
```

Должен быть идентичен по поведению:

``` yaml
parameters:
    kernel:
        debug: true
```

<!-- 2014-08-29T17:41Z https://github.com/kamilsk/CilexServiceProviders/issues/5#issuecomment-53907534
не очень хорошо поддерживается на уровне `incenteev/composer-parameter-handler`
-->
