<?php
return OctoLab\Common\Config\Util\ArrayHelper::merge(
    include 'parameters.php',
    include 'component/config.php',
    [
        'component' => [
            'parameter' => 'base component\'s parameter will be overwritten by root config',
            'placeholder_parameter' => '%placeholder%',
            'constant' => E_ALL,
        ],
    ]
);
