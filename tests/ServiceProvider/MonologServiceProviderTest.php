<?php

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
    public function registerSuccess(Application $app)
    {
        $app->register($this->getConfigServiceProviderForMonolog());
        $app->register(new MonologServiceProvider());
        self::assertInstanceOf(LoggerInterface::class, $app['logger']);
        self::assertInstanceOf(Logger::class, $app['loggers']['app']);
        self::assertInstanceOf(Logger::class, $app['loggers']['debug']);
        self::assertInstanceOf(Logger::class, $app['loggers']['db']);
        self::assertEquals($app['logger'], $app['loggers'][$app['config']['monolog:default_channel']]);
        self::assertEquals($app['console.name'], $app['logger']->getName());
    }

    /**
     * @test
     * @dataProvider applicationProvider
     *
     * @param Application $app
     */
    public function registerEmpty(Application $app)
    {
        $app->register(new MonologServiceProvider());
        try {
            $app->offsetGet('logger');
            self::fail(sprintf('%s exception expected.', \InvalidArgumentException::class));
        } catch (\InvalidArgumentException $e) {
            self::assertTrue(true);
        }
    }
}
