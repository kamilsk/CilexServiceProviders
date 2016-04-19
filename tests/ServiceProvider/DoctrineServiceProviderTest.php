<?php

declare(strict_types = 1);

namespace OctoLab\Cilex\ServiceProvider;

use Cilex\Application;
use Doctrine\DBAL\Tools\Console\Helper\ConnectionHelper;
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
        self::assertTrue($app->offsetExists('connections'));
        self::assertTrue($app->offsetExists('connection'));
        self::assertInstanceOf(ConnectionHelper::class, $app['console']->getHelperSet()->get('connection'));
    }
}
