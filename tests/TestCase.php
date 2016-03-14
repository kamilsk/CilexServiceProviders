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
    protected function getConfigServiceProvider($config, $extension, array $placeholders = ['placeholder' => 'test'])
    {
        return new ServiceProvider\ConfigServiceProvider($this->getConfigPath($config, $extension), $placeholders);
    }

    /**
     * @return ServiceProvider\ConfigServiceProvider
     */
    protected function getConfigServiceProviderForMonolog()
    {
        return $this->getConfigServiceProvider('monolog/config', 'yml', ['root_dir' => __DIR__]);
    }

    /**
     * @return ServiceProvider\ConfigServiceProvider
     */
    protected function getConfigServiceProviderForDoctrine()
    {
        return $this->getConfigServiceProvider('doctrine/config', 'yml');
    }

    /**
     * @return ServiceProvider\ConfigServiceProvider
     */
    protected function getConfigServiceProviderForCliMenu()
    {
        return $this->getConfigServiceProvider('cli-menu/config', 'yml');
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
