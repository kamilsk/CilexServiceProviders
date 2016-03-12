<?php

namespace OctoLab\Cilex;

use Cilex\Application as CilexApplication;

/**
 * @author Kamil Samigullin <kamil@samigullin.info>
 */
abstract class TestCase extends \PHPUnit_Framework_TestCase
{
    /**
     * @return array<int,CilexApplication[]>
     */
    public function applicationProvider()
    {
        return [
            [new CilexApplication('test')],
            [new Application('test')],
        ];
    }

    /**
     * @return array<int,ServiceProvider\ConfigServiceProvider[]>
     */
    public function doctrineConfigProvider()
    {
        return [
            [new ServiceProvider\ConfigServiceProvider($this->getConfigPath('doctrine/config'))],
        ];
    }

    /**
     * @return array<int,ServiceProvider\ConfigServiceProvider[]>
     */
    public function monologConfigProvider()
    {
        return [
            [
                new ServiceProvider\ConfigServiceProvider(
                    $this->getConfigPath('monolog/config'),
                    ['root_dir' => __DIR__]
                )
            ],
        ];
    }

    /**
     * @param string $config
     * @param string $extension
     *
     * @return string
     */
    protected function getConfigPath($config = 'config', $extension = 'yml')
    {
        return sprintf('%s/app/config/%s.%s', __DIR__, $config, $extension);
    }
}
