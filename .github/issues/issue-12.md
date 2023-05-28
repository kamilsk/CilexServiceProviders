---
code: null
id: MDU6SXNzdWU0MTU0NDYzMw==
databaseId: 41544633
number: 12
url: https://github.com/kamilsk/CilexServiceProviders/issues/12
title: "[master] ошибка в ConfigServiceProvider"
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
createdAt: 2014-08-29T22:18:59Z
updatedAt: 2015-02-01T12:09:43Z
lastEditedAt: null
closedAt: 2014-08-29T22:28:25Z
---

# [master] ошибка в ConfigServiceProvider

Placeholder может указывать на массив, тогда при замене возникает ошибка
`Notice: Array to string conversion in src/Provider/ConfigServiceProvider.php on line 64`, которая в свою очередь может поломать последующий код.

Проблема обнаружена в проекте https://github.com/kamilsk/SocialMonitoring при обновлении зависимости до версии 1.0.
