<?php
/**
 * @link http://www.octolab.org/
 * @copyright Copyright (c) 2013 OctoLab
 * @license http://www.octolab.org/license
 */

namespace OctoLab\Cilex\Test;

use Cilex\Application;
use OctoLab\Cilex\Provider\ConfigServiceProvider;

/**
 * @author Kamil Samigullin <kamil@samigullin.info>
 */
class ConfigServiceProviderTest extends \PHPUnit_Framework_TestCase
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
    public function parametersBehavior(Application $app)
    {
        $app->register(new ConfigServiceProvider(__DIR__ . '/app/config/config_parameters.yml'));
        $expected = [
            'component' => [
                'parameter' => 'test_parameter',
                'another_parameter' => 'test_another_parameter',
            ],
        ];
        $this->assertEquals($expected, $app['config']);
    }

    /**
     * @test
     * @dataProvider applicationProvider
     *
     * @param Application $app
     */
    public function placeholdersBehavior(Application $app)
    {
        $app->register(new ConfigServiceProvider(__DIR__ . '/app/config/config_placeholders.yml', [
            'another_parameter' => 'test_placeholder',
        ]));
        $expected = [
            'component' => [
                'parameter' => 'test_parameter',
                'another_parameter' => 'test_placeholder',
            ],
        ];
        $this->assertEquals($expected, $app['config']);
    }

    /**
     * @test
     * @dataProvider applicationProvider
     *
     * @param Application $app
     */
    public function overrideParameterBehavior(Application $app)
    {
        $app->register(new ConfigServiceProvider(__DIR__ . '/app/config/config_override.yml', [
            'root_dir' => __DIR__,
            'file' => 'test.txt',
        ]));
        $expected = [
            'component' => [
                'parameter' => sprintf('%s/path/to/%s', __DIR__, 'test.txt'),
            ],
        ];
        $this->assertEquals($expected, $app['config']);
    }

    /**
     * @test
     * @dataProvider applicationProvider
     *
     * @param Application $app
     */
    public function complexBehavior(Application $app)
    {
        $app->register(new ConfigServiceProvider(__DIR__ . '/app/config/config.yml', ['placeholder' => 'placeholder']));
        $expected = [
            'component' => [
                'base_parameter' => 'base parameter will not be overwritten',
                'parameter' => "base component's parameter will be overwritten by root config",
                'placeholder_parameter' => 'placeholder',
            ],
        ];
        $this->assertEquals($expected, $app['config']);
    }
}
