---
code: null
id: MDU6SXNzdWU0MDk0MjA5Ng==
databaseId: 40942096
number: 6
url: https://github.com/kamilsk/CilexServiceProviders/issues/6
title: "[master] ConfigServiceProvider поддержка placeholders внутри parameters"
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
createdAt: 2014-08-22T19:28:01Z
updatedAt: 2014-08-28T19:35:16Z
lastEditedAt: null
closedAt: 2014-08-28T19:35:16Z
---

# [master] ConfigServiceProvider поддержка placeholders внутри parameters

Необходимо реализовать поддержку установки в parameters специальных меток, которые можно прокидывать на уровне placeholders:

``` yml
parameters:
    path_to_file: %root_dir%/app/file.txt
```

``` php
$app->register(new ConfigServiceProvider(...), ['root_dir' => __DIR__]);
```
