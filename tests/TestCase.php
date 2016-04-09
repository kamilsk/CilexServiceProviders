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
     * @param array $placeholders
     *
     * @return ServiceProvider\ConfigServiceProvider
     */
    protected function getConfigServiceProvider($config = 'config', array $placeholders = ['placeholder' => 'test'])
    {
        return new ServiceProvider\ConfigServiceProvider($this->getConfigPath($config), $placeholders);
    }

    /**
     * @return ServiceProvider\ConfigServiceProvider
     */
    protected function getConfigServiceProviderForMonolog()
    {
        return $this->getConfigServiceProvider('monolog/config', ['root_dir' => __DIR__]);
    }

    /**
     * @return ServiceProvider\ConfigServiceProvider
     */
    protected function getConfigServiceProviderForDoctrine()
    {
        return $this->getConfigServiceProvider('doctrine/config');
    }

    /**
     * @return ServiceProvider\ConfigServiceProvider
     */
    protected function getConfigServiceProviderForCliMenu()
    {
        return $this->getConfigServiceProvider('cli-menu/config');
    }

    /**
     * @param string $config
     *
     * @return string
     */
    protected function getConfigPath($config = 'config')
    {
        return sprintf('%s/app/config/%s.yml', __DIR__, $config);
    }

    /**
     * @return bool
     */
    protected function isValidEnvironment()
    {
        return defined('HHVM_VERSION') || (defined('PHP_VERSION') && version_compare(PHP_VERSION, '5.6', '>='));
    }
}
