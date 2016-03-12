<?php

namespace OctoLab\Cilex;

use Cilex\Application as CilexApplication;
use OctoLab\Cilex\ServiceProvider;

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
     * @param string $config
     * @param string $extension
     * @param array $placeholders
     *
     * @return ServiceProvider\ConfigServiceProvider
     */
    public function getConfigServiceProvider($config, $extension, array $placeholders = ['placeholder' => 'test'])
    {
        return new ServiceProvider\ConfigServiceProvider($this->getConfigPath($config, $extension), $placeholders);
    }

    /**
     * @return ServiceProvider\ConfigServiceProvider
     */
    public function getConfigServiceProviderForMonolog()
    {
        return $this->getConfigServiceProvider('monolog/config', 'yml', ['root_dir' => __DIR__]);
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
