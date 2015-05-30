<?php

namespace OctoLab\Cilex\Tests\Provider;

use Cilex\Application;
use OctoLab\Cilex\Provider\ConfigServiceProvider;
use OctoLab\Cilex\Provider\DoctrineServiceProvider;
use OctoLab\Cilex\Tests\TestCase;

/**
 * phpunit src/Tests/Provider/DoctrineServiceProviderTest.php
 *
 * @author Kamil Samigullin <kamil@samigullin.info>
 */
class DoctrineServiceProviderTest extends TestCase
{
    /**
     * @test
     * @dataProvider doctrineConfigProvider
     *
     * @param ConfigServiceProvider $config
     */
    public function supportConfigProvider(ConfigServiceProvider $config)
    {
        $app = new Application('Test');
        $app->register($config);
        $app->register(new DoctrineServiceProvider());
        self::assertEquals($app['config']['doctrine']['dbal']['connections'], $app['dbs.options']);
        self::assertEquals($app['config']['doctrine']['dbal']['default_connection'], $app['dbs.default']);
    }

    /**
     * @test
     * @dataProvider doctrineConfigProvider
     *
     * @param ConfigServiceProvider $config
     */
    public function supportHelperConnection(ConfigServiceProvider $config)
    {
        // default behavior
        $app = new Application('Test');
        $app->register($config);
        $app->register(new DoctrineServiceProvider());
        self::assertFalse($app->offsetGet('console')->getHelperSet()->has('connection'));
        // default connection
        $app = new Application('Test');
        $app->register($config);
        $app->register(new DoctrineServiceProvider(true));
        self::assertEquals(
            $app->offsetGet('db'),
            $app->offsetGet('console')->getHelperSet()->get('connection')->getConnection()
        );
        // specified connection
        $app = new Application('Test');
        $app->register($config);
        $app->register(new DoctrineServiceProvider('sqlite'));
        self::assertEquals(
            $app->offsetGet('dbs')['sqlite'],
            $app->offsetGet('console')->getHelperSet()->get('connection')->getConnection()
        );
    }
}
