---
code: null
id: MDU6SXNzdWU0MDI4MjAxNg==
databaseId: 40282016
number: 1
url: https://github.com/kamilsk/CilexServiceProviders/issues/1
title: "[master] ConfigServiceProvider"
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
createdAt: 2014-08-14T18:11:55Z
updatedAt: 2014-08-20T19:13:35Z
lastEditedAt: null
closedAt: 2014-08-20T19:13:35Z
---

# [master] ConfigServiceProvider

Недостатки текущего провайдера:
- нет поддержки imports
- нет поддержки parameters
- нет поддержки placeholders

Roadmap:
- [x] imports
- [x] parameters
- [x] placeholders

Test:
- [x] imports
- [x] parameters
- [x] placeholders

<!-- 2014-08-15T18:26Z https://github.com/kamilsk/CilexServiceProviders/issues/1#issuecomment-52341095
система утверждений:
- imports это набор ссылок на файлы, который находится в верхней части конфигурационного файла (в том числе и вложенного)
- все файлы из imports подключаются по порядку
- самые верхние файлы имеют низший приоритет определения конфигурации и могут быть перезаписаны последующими
- parameters это набор placeholders, которые могут быть перезаписаны явно переданным набором placeholders
-->
