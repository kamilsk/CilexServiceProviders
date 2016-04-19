<?php

declare(strict_types = 1);

namespace OctoLab\Cilex\ServiceProvider;

use Cilex\Application;
use OctoLab\Cilex\TestCase;

/**
 * @author Kamil Samigullin <kamil@samigullin.info>
 */
class ConfigServiceProviderTest extends TestCase
{
    /**
     * @test
     * @dataProvider applicationProvider
     *
     * @data Application $app
     */
    public function register(Application $app)
    {
        $app->register($this->getConfigServiceProvider());
        self::assertTrue($app->offsetExists('config'));
        self::assertTrue($app->offsetGet('console')->has('config:dump'));
    }
}
