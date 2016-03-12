<?php
return OctoLab\Common\Util\ArrayHelper::merge(
    include __DIR__ . '/parameters.php',
    include __DIR__ . '/component/config.php',
    [
        'app' => [
            'placeholder_parameter' => '%placeholder%',
            'constant' => E_ALL,
        ],
        'component' => [
            'parameter' => 'base component\'s parameter will be overwritten by root config',
        ],
    ]
);
