<?php
/**
 * @link http://www.octolab.org/
 * @copyright Copyright (c) 2013 OctoLab
 * @license http://www.octolab.org/license
 */

namespace OctoLab\Cilex\Tests\Config;

use OctoLab\Cilex\Config\YamlFileLoader;
use Symfony\Component\Config\FileLocator;

/**
 * @author Kamil Samigullin <kamil@samigullin.info>
 */
class YamlFileLoaderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @return YamlFileLoader[]
     */
    public function loaderProvider()
    {
        return [
            [new YamlFileLoader(new FileLocator())],
        ];
    }

    /**
     * @test
     * @dataProvider loaderProvider
     *
     * @param YamlFileLoader $loader
     */
    public function supported(YamlFileLoader $loader)
    {
        $this->assertTrue($loader->supports('/some/path/to/supported.yml'));
    }

    /**
     * @test
     * @dataProvider loaderProvider
     *
     * @param YamlFileLoader $loader
     */
    public function unsupported(YamlFileLoader $loader)
    {
        $this->assertFalse($loader->supports('/some/path/to/unsupported.xml'));
    }

    /**
     * @test
     * @dataProvider loaderProvider
     *
     * @param YamlFileLoader $loader
     */
    public function content(YamlFileLoader $loader)
    {
        $config = sprintf('%s/app/config/config.yml', dirname(__DIR__));
        $loader->load($config);
        $expected = [
            [
                'imports' => [
                    [
                        'resource' => 'parameters.yml',
                    ],
                    [
                        'resource' => 'component/config.yml',
                    ],
                ],
                'component' => [
                    'parameter' => "base component's parameter will be overwritten by root config",
                    'placeholder_parameter' => '%placeholder%',
                ],
            ],
            [
                'parameters' => [
                    'parameter' => 'will overwrite parameter',
                ],
            ],
            [
                'imports' => [
                    [
                        'resource' => 'base_config.yml',
                    ],
                ],
                'component' => [
                    'parameter' => "base component's parameter will be overwritten by component config",
                ],
            ],
            [
                'component' => [
                    'parameter' => 'base parameter will be overwritten',
                    'base_parameter' => 'base parameter will not be overwritten',
                ],
            ],
        ];
        $this->assertEquals($expected, $loader->getContent());
    }
}
