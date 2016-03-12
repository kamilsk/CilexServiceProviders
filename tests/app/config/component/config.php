<?php
return OctoLab\Common\Util\ArrayHelper::merge(
    include __DIR__ . '/base.php',
    [
        'component' => [
            'parameter' => 'base component\'s parameter will be overwritten by component config',
        ],
    ]
);
