<?php
return OctoLab\Cilex\Config\Util\ArrayHelper::merge(
    include 'base.php',
    [
        'component' => [
            'parameter' => 'base component\'s parameter will be overwritten by component config',
        ],
    ]
);
