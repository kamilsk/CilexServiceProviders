<?php

namespace OctoLab\Cilex;

use Cilex\Application as CilexApplication;

/**
 * @author Kamil Samigullin <kamil@samigullin.info>
 */
class ApplicationTest extends TestCase
{
    /**
     * @test
     */
    public function registerNew()
    {
        $app = new Application('Test');
        $app->register(new ServiceProvider\ConfigServiceProvider($this->getConfigPath()));
        $app->register(new ServiceProvider\ConfigServiceProvider($this->getConfigPath('empty')));
    }

    /**
     * @test
     */
    public function registerOld()
    {
        $app = new CilexApplication('Test');
        $app->register(new ServiceProvider\ConfigServiceProvider($this->getConfigPath()));
        $app->register(new ServiceProvider\ConfigServiceProvider($this->getConfigPath('empty')));
    }
}
