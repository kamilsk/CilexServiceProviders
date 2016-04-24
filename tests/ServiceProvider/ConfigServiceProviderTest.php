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
        $expected = [
            'app' => [
                'placeholder_parameter' => 'test',
                'constant' => E_ALL,
            ],
            'component' => [
                'parameter' => 'base component\'s parameter will be overwritten by root config',
                'base_parameter' => 'base parameter will not be overwritten',
            ],
        ];
        $app->register($this->getConfigServiceProvider());
        foreach ($expected as $key => $value) {
            self::assertEquals($value, $app['config'][$key]);
        }
        self::assertTrue($app->offsetGet('console')->has('config:dump'));
    }
}
