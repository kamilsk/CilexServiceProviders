<?php

namespace OctoLab\Cilex\ServiceProvider;

use Cilex\Application;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Types\Type;
use OctoLab\Cilex\TestCase;

/**
 * @author Kamil Samigullin <kamil@samigullin.info>
 */
class DoctrineServiceProviderTest extends TestCase
{
    /**
     * @test
     * @dataProvider applicationProvider
     *
     * @param Application $app
     */
    public function registerSuccess(Application $app)
    {
        $app->register($this->getConfigServiceProviderForDoctrine());
        $app->register(new DoctrineServiceProvider());
        self::assertInstanceOf(Connection::class, $app['connection']);
        self::assertInstanceOf(Connection::class, $app['connections']['mysql']);
        self::assertInstanceOf(Connection::class, $app['connections']['sqlite']);
        self::assertEquals($app['connection'], $app['connections'][$app['config']['doctrine:dbal:default_connection']]);
        foreach ($app['config']['doctrine:dbal:types'] as $type => $_) {
            self::assertTrue(Type::hasType($type));
        }
        self::assertEquals($app['connection'], $app['console']->getHelperSet()->get('connection')->getConnection());
    }

    /**
     * @test
     * @dataProvider applicationProvider
     *
     * @param Application $app
     */
    public function registerFailure(Application $app)
    {
        try {
            $app->register(new DoctrineServiceProvider());
            self::fail(sprintf('%s exception expected.', \InvalidArgumentException::class));
        } catch (\InvalidArgumentException $e) {
            self::assertTrue(true);
        }
    }

    /**
     * @test
     * @dataProvider applicationProvider
     *
     * @param Application $app
     */
    public function registerEmpty(Application $app)
    {
        $app->register($this->getConfigServiceProvider('config', 'yml'));
        $app->register(new DoctrineServiceProvider());
        try {
            $app->offsetGet('connection');
            self::fail(sprintf('%s exception expected.', \InvalidArgumentException::class));
        } catch (\InvalidArgumentException $e) {
            self::assertTrue(true);
        }
    }
}
