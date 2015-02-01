<?php
/**
 * @link http://www.octolab.org/
 * @copyright Copyright (c) 2013 OctoLab
 * @license http://www.octolab.org/license
 */

namespace OctoLab\Cilex\Tests;

use OctoLab\Cilex\Provider\ConfigServiceProvider;

/**
 * @author Kamil Samigullin <kamil@samigullin.info>
 */
abstract class TestCase extends \PHPUnit_Framework_TestCase
{
    /**
     * @return ConfigServiceProvider[]
     */
    public function doctrineConfigProvider()
    {
        return [
            [new ConfigServiceProvider($this->getConfigPath('doctrine/config'))],
        ];
    }

    /**
     * @return ConfigServiceProvider[]
     */
    public function monologConfigProvider()
    {
        return [
            [
                new ConfigServiceProvider(
                    $this->getConfigPath('monolog/config'),
                    ['root_dir' => __DIR__]
                )
            ],
        ];
    }

    /**
     * @param string $config
     *
     * @return string
     */
    protected function getConfigPath($config)
    {
        return sprintf('%s/app/%s.yml', __DIR__, $config);
    }
}
