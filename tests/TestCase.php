<?php

namespace OctoLab\Cilex;

/**
 * @author Kamil Samigullin <kamil@samigullin.info>
 */
abstract class TestCase extends \PHPUnit_Framework_TestCase
{
    /**
     * @return array[]
     */
    public function doctrineConfigProvider()
    {
        return [
            [new ServiceProvider\ConfigServiceProvider($this->getConfigPath('doctrine/config'))],
        ];
    }

    /**
     * @return array[]
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
     * @return array[]
     */
    public function monologCascadeConfigProvider()
    {
        return [
            [
                new ServiceProvider\ConfigServiceProvider(
                    $this->getConfigPath('monolog/cascade'),
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
