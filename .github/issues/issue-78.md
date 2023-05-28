---
code: null
id: MDU6SXNzdWUxMjQwNDYxNjg=
databaseId: 124046168
number: 78
url: https://github.com/kamilsk/CilexServiceProviders/issues/78
title: "new getConfig functionality"
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
createdAt: 2015-12-28T10:06:20Z
updatedAt: 2016-01-05T09:17:13Z
lastEditedAt: null
closedAt: 2016-01-05T09:17:13Z
---

# new getConfig functionality

`Command::getConfig('some:component:config', [])`

<!-- 2015-12-28T11:40Z https://github.com/kamilsk/CilexServiceProviders/issues/78#issuecomment-167550119
draw:

``` php
    public function getConfig($path = null, $default = null)
    {
        $config = parent::getConfig();
        if ($path) {
            $chain = explode(':', $path);
            $current = $config;
            foreach ($chain as $i => $key) {
                if (!is_array($current)) {
                    throw new \InvalidArgumentException(
                        sprintf('Invalid path "%s" in config.', implode(':', array_slice($chain, 0, $i)))
                    );
                }
                if (isset($current[$key])) {
                    $current = $current[$key];
                } else {
                    $current = $default;
                    break;
                }
            }
            $config = $current;
        }
        return $config;
    }
```
-->
