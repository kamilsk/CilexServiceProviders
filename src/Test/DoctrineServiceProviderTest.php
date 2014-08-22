<?php
/**
 * @link http://www.octolab.org/
 * @copyright Copyright (c) 2013 OctoLab
 * @license http://www.octolab.org/license
 */

namespace OctoLab\Cilex\Test;

use Cilex\Application;
use OctoLab\Cilex\Provider\ConfigServiceProvider;
use OctoLab\Cilex\Provider\DoctrineServiceProvider;

/**
 * @author Kamil Samigullin <kamil@samigullin.info>
 */
class DoctrineServiceProviderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @return Application[]
     */
    public function applicationProvider()
    {
        return [
            [new Application('Test')],
        ];
    }

    /**
     * @test
     * @dataProvider applicationProvider
     *
     * @param Application $app
     */
    public function configSupportBehavior(Application $app)
    {
        $app->register(new ConfigServiceProvider(__DIR__ . '/app/doctrine/config.yml'));
        $app->register(new DoctrineServiceProvider());
        $this->assertEquals($app['config']['doctrine']['dbal']['connections'], $app['dbs.options']);
        $this->assertEquals($app['config']['doctrine']['dbal']['default_connection'], $app['dbs.default']);
    }
}
