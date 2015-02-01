<?php
/**
 * @link http://www.octolab.org/
 * @copyright Copyright (c) 2013 OctoLab
 * @license http://www.octolab.org/license
 */

namespace OctoLab\Cilex\Tests\Provider;

use Cilex\Application;
use OctoLab\Cilex\Provider\ConfigServiceProvider;
use OctoLab\Cilex\Provider\DoctrineServiceProvider;
use OctoLab\Cilex\Tests\TestCase;

/**
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
        $this->assertEquals($app['config']['doctrine']['dbal']['connections'], $app['dbs.options']);
        $this->assertEquals($app['config']['doctrine']['dbal']['default_connection'], $app['dbs.default']);
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
        $this->assertFalse($app->offsetGet('console')->getHelperSet()->has('connection'));
        // default connection
        $app = new Application('Test');
        $app->register($config);
        $app->register(new DoctrineServiceProvider(true));
        $this->assertEquals(
            $app->offsetGet('db'),
            $app->offsetGet('console')->getHelperSet()->get('connection')->getConnection()
        );
        // specified connection
        $app = new Application('Test');
        $app->register($config);
        $app->register(new DoctrineServiceProvider('sqlite'));
        $this->assertEquals(
            $app->offsetGet('dbs')['sqlite'],
            $app->offsetGet('console')->getHelperSet()->get('connection')->getConnection()
        );
    }
}
