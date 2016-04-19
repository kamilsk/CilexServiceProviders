<?php

declare(strict_types = 1);

namespace OctoLab\Cilex\ServiceProvider;

use Cilex\Application;
use OctoLab\Cilex\TestCase;

/**
 * @author Kamil Samigullin <kamil@samigullin.info>
 */
class MonologServiceProviderTest extends TestCase
{
    /**
     * @test
     * @dataProvider applicationProvider
     *
     * @param Application $app
     */
    public function register(Application $app)
    {
        $app->register($this->getConfigServiceProviderForMonolog());
        $app->register(new MonologServiceProvider());
        self::assertTrue($app->offsetExists('loggers'));
        self::assertTrue($app->offsetExists('logger'));
        self::assertTrue($app->offsetExists('monolog.bridge'));
        self::assertTrue($app->offsetExists('app.name'));
        self::assertEquals($app['console.name'], $app['app.name']);
    }
}
