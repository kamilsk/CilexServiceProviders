<?php
/**
 * @link http://www.octolab.org/
 * @copyright Copyright (c) 2013 OctoLab
 * @license http://www.octolab.org/license
 */

namespace OctoLab\Cilex\Tests\Provider;

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
    public function substituteParameters(Application $app)
    {
        $app->register(new ConfigServiceProvider($this->getConfigPath('config_parameters')));
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
    public function substitutePlaceholders(Application $app)
    {
        $app->register(new ConfigServiceProvider($this->getConfigPath('config_placeholders'), [
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
    public function overrideParameters(Application $app)
    {
        $app->register(new ConfigServiceProvider($this->getConfigPath('config_override'), [
            'root_dir' => realpath(dirname(__DIR__)),
            'file' => 'test.txt',
        ]));
        $expected = [
            'component' => [
                'parameter' => sprintf('%s/path/to/%s', realpath(dirname(__DIR__)), 'test.txt'),
                'options' => [1, 2, 3],
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
    public function combineParametersAndPlaceholders(Application $app)
    {
        $app->register(new ConfigServiceProvider($this->getConfigPath('config'), ['placeholder' => 'placeholder']));
        $expected = [
            'component' => [
                'base_parameter' => 'base parameter will not be overwritten',
                'parameter' => "base component's parameter will be overwritten by root config",
                'placeholder_parameter' => 'placeholder',
            ],
        ];
        $this->assertEquals($expected, $app['config']);
    }

    /**
     * @param string $config
     *
     * @return string
     */
    private function getConfigPath($config)
    {
        return sprintf('%s/app/config/%s.yml', realpath(dirname(__DIR__)), $config);
    }
}
