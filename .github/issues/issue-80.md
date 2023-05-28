---
code: null
id: MDU6SXNzdWUxMjQwNDY0Nzg=
databaseId: 124046478
number: 80
url: https://github.com/kamilsk/CilexServiceProviders/issues/80
title: "new getLogger functionality"
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
createdAt: 2015-12-28T10:08:41Z
updatedAt: 2016-01-07T11:40:31Z
lastEditedAt: null
closedAt: 2016-01-07T11:40:27Z
---

# new getLogger functionality

`Command::getLogger('channel')`

<!-- 2015-12-28T11:41Z https://github.com/kamilsk/CilexServiceProviders/issues/80#issuecomment-167550202
draw:

``` php
    public function getLogger($channel = null)
    {
        if ($channel) {
            /** @see https://github.com/kamilsk/CilexServiceProviders/issues/54 */
            $logger = new Logger($channel);
            $resolver = new ConfigResolver();
            $resolver->resolve($this->getConfig('monolog'));
            foreach ($resolver->getProcessors() as $processor) {
                $logger->pushProcessor($processor);
            }
        } else {
            $logger = parent::getLogger();
        }
        return $logger;
    }
```
-->
