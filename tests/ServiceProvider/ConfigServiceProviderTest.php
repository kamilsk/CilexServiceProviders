<?php

namespace OctoLab\Cilex\ServiceProvider;

use Cilex\Application as CilexApplication;
use OctoLab\Cilex\TestCase;

/**
 * @author Kamil Samigullin <kamil@samigullin.info>
 */
class ConfigServiceProviderTest extends TestCase
{
    /** @var array */
    private $expected = [
        'app' => [
            'placeholder_parameter' => 'test',
            'constant' => E_ALL,
        ],
        'component' => [
            'parameter' => 'base component\'s parameter will be overwritten by root config',
            'base_parameter' => 'base parameter will not be overwritten',
        ],
    ];

    /**
     * @test
     * @dataProvider applicationProvider
     *
     * @param CilexApplication $app
     */
    public function registerJsonSuccess(CilexApplication $app)
    {
        $app->register(new ConfigServiceProvider($this->getConfigPath('config', 'json'), ['placeholder' => 'test']));
        foreach ($this->expected as $key => $value) {
            self::assertEquals($value, $app['config'][$key]);
        }
    }

    /**
     * @test
     * @dataProvider applicationProvider
     *
     * @param CilexApplication $app
     */
    public function registerPhpSuccess(CilexApplication $app)
    {
        $app->register(new ConfigServiceProvider($this->getConfigPath('config', 'php'), ['placeholder' => 'test']));
        foreach ($this->expected as $key => $value) {
            self::assertEquals($value, $app['config'][$key]);
        }
    }

    /**
     * @test
     * @dataProvider applicationProvider
     *
     * @param CilexApplication $app
     */
    public function registerYamlSuccess(CilexApplication $app)
    {
        $app->register(new ConfigServiceProvider($this->getConfigPath('config', 'yml'), ['placeholder' => 'test']));
        foreach ($this->expected as $key => $value) {
            self::assertEquals($value, $app['config'][$key]);
        }
    }


    /**
     * @test
     * @dataProvider applicationProvider
     *
     * @param CilexApplication $app
     */
    public function registerFail(CilexApplication $app)
    {
        try {
            $app->register(new ConfigServiceProvider($this->getConfigPath('config', 'xml')));
            $app->offsetGet('config');
            self::fail(sprintf('%s exception expected.', \DomainException::class));
        } catch (\DomainException $e) {
            self::assertTrue(true);
        }
    }
}
