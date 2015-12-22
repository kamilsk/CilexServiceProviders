<?php

namespace Test\OctoLab\Cilex;

use Cilex\Application as OldApplication;
use OctoLab\Cilex\Application as NewApplication;
use OctoLab\Cilex\ServiceProvider\ConfigServiceProvider;

/**
 * phpunit tests/ApplicationTest.php
 *
 * @author Kamil Samigullin <kamil@samigullin.info>
 */
class ApplicationTest extends TestCase
{
    /**
     * @test
     */
    public function registerNew()
    {
        $app = new NewApplication('Test');
        $app->register(new ConfigServiceProvider($this->getConfigPath()));
        self::assertNotEmpty($app->offsetGet('config'));
        $app->register(new ConfigServiceProvider($this->getConfigPath('empty')));
        self::assertNotEmpty($app->offsetGet('config'));
    }

    /**
     * @test
     */
    public function registerOld()
    {
        $app = new OldApplication('Test');
        $app->register(new ConfigServiceProvider($this->getConfigPath()));
        self::assertNotEmpty($app->offsetGet('config'));
        $app->register(new ConfigServiceProvider($this->getConfigPath('empty')));
        self::assertEmpty($app->offsetGet('config'));
    }
}
