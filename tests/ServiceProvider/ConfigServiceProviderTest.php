<?php

namespace Test\OctoLab\Cilex\ServiceProvider;

use Cilex\Application;
use OctoLab\Cilex\ServiceProvider\ConfigServiceProvider;
use Test\OctoLab\Cilex\TestCase;

/**
 * phpunit tests/ServiceProvider/ConfigServiceProviderTest.php
 *
 * @author Kamil Samigullin <kamil@samigullin.info>
 */
class ConfigServiceProviderTest extends TestCase
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
        self::assertEquals($expected, $app['config']);
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
        self::assertEquals($expected, $app['config']);
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
            'root_dir' => dirname(__DIR__),
            'file' => 'test.txt',
        ]));
        $expected = [
            'component' => [
                'parameter' => sprintf('%s/path/to/%s', dirname(__DIR__), 'test.txt'),
                'options' => [1, 2, 3],
            ],
        ];
        self::assertEquals($expected, $app['config']);
    }

    /**
     * @test
     * @dataProvider applicationProvider
     *
     * @param Application $app
     */
    public function combineParametersAndPlaceholders(Application $app)
    {
        $app->register(
            new ConfigServiceProvider($this->getConfigPath(), ['placeholder' => 'placeholder'])
        );
        $expected = [
            'component' => [
                'base_parameter' => 'base parameter will not be overwritten',
                'parameter' => 'base component\'s parameter will be overwritten by root config',
                'placeholder_parameter' => 'placeholder',
                'constant' => E_ALL,
            ],
        ];
        self::assertEquals($expected, $app['config']);
    }

    /**
     * @test
     * @dataProvider applicationProvider
     *
     * @param Application $app
     */
    public function phpConfigSupport(Application $app)
    {
        $app->register(
            new ConfigServiceProvider($this->getConfigPath('config', 'php'), ['placeholder' => 'placeholder'])
        );
        $expected = [
            'component' => [
                'base_parameter' => 'base parameter will not be overwritten',
                'parameter' => 'base component\'s parameter will be overwritten by root config',
                'placeholder_parameter' => 'placeholder',
                'constant' => E_ALL,
            ],
        ];
        self::assertEquals($expected, $app['config']);
    }

    /**
     * @test
     * @dataProvider applicationProvider
     *
     * @param Application $app
     */
    public function jsonConfigSupport(Application $app)
    {
        $app->register(
            new ConfigServiceProvider($this->getConfigPath('config', 'json'), ['placeholder' => 'placeholder'])
        );
        $expected = [
            'component' => [
                'parameter' => 'base component\'s parameter',
                'placeholder_parameter' => 'placeholder',
                'constant' => E_ALL,
            ],
        ];
        self::assertEquals($expected, $app['config']);
    }

    /**
     * @test
     * @dataProvider applicationProvider
     * @expectedException \DomainException
     *
     * @param Application $app
     */
    public function throwDomainException(Application $app)
    {
        $app->register(
            new ConfigServiceProvider($this->getConfigPath('config', 'xml'), ['placeholder' => 'placeholder'])
        );
        $app->offsetGet('config');
    }
}
