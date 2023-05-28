---
code: null
id: MDU6SXNzdWU0MjcyMzMzMQ==
databaseId: 42723331
number: 20
url: https://github.com/kamilsk/CilexServiceProviders/issues/20
title: "[master] передавать имя соединения"
issueType: null
labels: []
assignees:
  - kamilsk
milestone: "[[milestone-2]]"
parent: null
issueFields: {}
state: CLOSED
stateReason: COMPLETED
duplicateOf: null
createdAt: 2014-09-14T15:43:58Z
updatedAt: 2014-11-09T18:07:32Z
lastEditedAt: null
closedAt: 2014-11-09T18:07:32Z
---

# [master] передавать имя соединения

Добавить возможность указывать соединение для [`ConnectionHelper`](../../src/Provider/DoctrineServiceProvider.php#L28), при этом сохранив обратную совместимость:
- null по умолчанию
- если строка, то ищем соединение
- если булево, то берем по умолчанию
