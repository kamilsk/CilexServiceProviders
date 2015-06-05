<?php

namespace OctoLab\Cilex\Tests\Config;

use OctoLab\Cilex\Config\Parser\DipperYamlParser;
use OctoLab\Cilex\Config\YamlFileLoader;
use OctoLab\Cilex\Tests\TestCase;
use Symfony\Component\Config\FileLocator;

/**
 * phpunit src/Tests/Config/YamlFileLoaderTest.php
 *
 * @author Kamil Samigullin <kamil@samigullin.info>
 */
class YamlFileLoaderTest extends TestCase
{
    /**
     * @return YamlFileLoader[]
     */
    public function loaderProvider()
    {
        return [
            [new YamlFileLoader(new FileLocator())],
            [new YamlFileLoader(new FileLocator(), new DipperYamlParser())],
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
        self::assertTrue($loader->supports('/some/path/to/supported.yml'));
    }

    /**
     * @test
     * @dataProvider loaderProvider
     *
     * @param YamlFileLoader $loader
     */
    public function unsupported(YamlFileLoader $loader)
    {
        self::assertFalse($loader->supports('/some/path/to/unsupported.xml'));
    }

    /**
     * @test
     * @dataProvider loaderProvider
     *
     * @param YamlFileLoader $loader
     */
    public function content(YamlFileLoader $loader)
    {
        $loader->load($this->getConfigPath());
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
                    'parameter' => 'base component\'s parameter will be overwritten by root config',
                    'placeholder_parameter' => '%placeholder%',
                    'constant' => 'const(E_ALL)',
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
                        'resource' => 'base.yml',
                    ],
                ],
                'component' => [
                    'parameter' => 'base component\'s parameter will be overwritten by component config',
                ],
            ],
            [
                'component' => [
                    'parameter' => 'base parameter will be overwritten',
                    'base_parameter' => 'base parameter will not be overwritten',
                ],
            ],
        ];
        self::assertEquals($expected, $loader->getContent());
    }
}
