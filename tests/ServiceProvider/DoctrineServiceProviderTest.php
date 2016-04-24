<?php

declare(strict_types = 1);

namespace OctoLab\Cilex\ServiceProvider;

use Cilex\Application;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Tools\Console\Helper\ConnectionHelper;
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
    public function register(Application $app)
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
        self::assertInstanceOf(ConnectionHelper::class, $app['console']->getHelperSet()->get('connection'));
    }
}
