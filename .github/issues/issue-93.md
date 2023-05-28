---
code: null
id: MDU6SXNzdWUxNDIwNjc1MTY=
databaseId: 142067516
number: 93
url: https://github.com/kamilsk/CilexServiceProviders/issues/93
title: "support callable as array of commands"
issueType: null
labels: []
assignees:
  - kamilsk
milestone: "[[milestone-12]]"
parent: null
issueFields: {}
state: CLOSED
stateReason: COMPLETED
duplicateOf: null
createdAt: 2016-03-19T14:42:59Z
updatedAt: 2016-03-20T09:02:47Z
lastEditedAt: null
closedAt: 2016-03-20T09:02:47Z
---

# support callable as array of commands

https://github.com/kamilsk/CilexServiceProviders/blob/master/docs/AppAndCommands.md#configuration-example

<!-- 2016-03-19T14:57Z https://github.com/kamilsk/CilexServiceProviders/issues/93#issuecomment-198718182
Required example in [Click](https://github.com/octolab/Click):

``` yaml
cli_menu:
  title: %app_name% Menu
  items:
  - text: Install demo content
    commands:
    - { name: "click:set", options: { env: demo, urn: /click, url: https://github.com/octolab/Click, alias: click } }
    - { name: "click:set", options: { env: demo, urn: /google, url: https://www.google.com/ } }
    - { name: "click:set", options: { env: demo, urn: /yandex, url: http://www.yandex.ru/ } }
    - { name: "click:remove", options: { env: demo, urn: /google } }
```
-->
