<?php

namespace Test\OctoLab\Cilex\ServiceProvider;

use Cilex\Application;
use Doctrine\DBAL\Types\Type;
use OctoLab\Cilex\ServiceProvider\ConfigServiceProvider;
use OctoLab\Cilex\ServiceProvider\DoctrineServiceProvider;
use Test\OctoLab\Cilex\TestCase;

/**
 * phpunit tests/ServiceProvider/DoctrineServiceProviderTest.php
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
    public function configSupport(ConfigServiceProvider $config)
    {
        $app = new Application('Test');
        $app->register($config);
        $app->register(new DoctrineServiceProvider());
        self::assertEquals($app['config']['doctrine']['dbal']['connections'], $app['dbs.options']);
        self::assertEquals($app['config']['doctrine']['dbal']['default_connection'], $app['dbs.default']);
        self::assertTrue(Type::hasType('enum'));
    }

    /**
     * @test
     * @dataProvider doctrineConfigProvider
     *
     * @param ConfigServiceProvider $config
     */
    public function helperConnectionSupport(ConfigServiceProvider $config)
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
