---
code: null
id: MDU6SXNzdWU1OTM4OTI0OA==
databaseId: 59389248
number: 35
url: https://github.com/kamilsk/CilexServiceProviders/issues/35
title: "refactoring MonologServiceProvider"
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
createdAt: 2015-03-01T09:15:49Z
updatedAt: 2015-03-22T13:08:35Z
lastEditedAt: null
closedAt: 2015-03-22T13:08:35Z
---

# refactoring MonologServiceProvider

Bad architecture:
https://github.com/kamilsk/CilexServiceProviders/blob/master/src/Command/Command.php#L83-L93
https://github.com/kamilsk/CilexServiceProviders/blob/master/src/Provider/MonologServiceProvider.php#L54-L56
and `class_exists('\Symfony\Bridge\Monolog\Handler\ConsoleHandler', false)` always is false, because class not loaded
