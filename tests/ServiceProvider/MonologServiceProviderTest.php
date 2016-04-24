<?php

declare(strict_types = 1);

namespace OctoLab\Cilex\ServiceProvider;

use Cilex\Application;
use Monolog\Logger;
use OctoLab\Cilex\TestCase;
use Psr\Log\LoggerInterface;

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
        self::assertInstanceOf(LoggerInterface::class, $app['logger']);
        self::assertInstanceOf(Logger::class, $app['loggers']['app']);
        self::assertInstanceOf(Logger::class, $app['loggers']['debug']);
        self::assertInstanceOf(Logger::class, $app['loggers']['db']);
        self::assertEquals($app['logger'], $app['loggers'][$app['config']['monolog:default_channel']]);
        self::assertEquals($app['app.name'], $app['logger']->getName());
        self::assertContains($app['console.name'], $app['app.name']);
        self::assertTrue($app->offsetExists('monolog.bridge'));
    }
}
