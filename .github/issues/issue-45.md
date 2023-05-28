---
code: null
id: MDU6SXNzdWU4MjkwOTM5MA==
databaseId: 82909390
number: 45
url: https://github.com/kamilsk/CilexServiceProviders/issues/45
title: "add .gitattributes to reduce the size of the package"
issueType: null
labels: []
assignees:
  - kamilsk
milestone: "[[milestone-5]]"
parent: null
issueFields: {}
state: CLOSED
stateReason: COMPLETED
duplicateOf: null
createdAt: 2015-05-30T20:47:42Z
updatedAt: 2015-06-01T08:53:11Z
lastEditedAt: null
closedAt: 2015-06-01T08:53:11Z
---

# add .gitattributes to reduce the size of the package

```
docs/*                 export-ignore
src/Tests/*            export-ignore
.gitattributes         export-ignore
.gitignore             export-ignore
.travis.yml            export-ignore
autoload.php.dist      export-ignore
phpunit.xml.dist       export-ignore
```
