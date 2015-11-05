<?php

namespace OctoLab\Cilex\Tests\Monolog;

use OctoLab\Cilex\Config\Loader\YamlFileLoader;
use OctoLab\Cilex\Config\Parser\SymfonyYamlParser;
use OctoLab\Cilex\Config\YamlConfig;
use OctoLab\Cilex\Monolog\ConfigResolver;
use OctoLab\Cilex\Tests\TestCase;
use Symfony\Component\Config\FileLocator;

/**
 * phpunit src/Tests/Monolog/ConfigResolverTest.php
 *
 * @author Kamil Samigullin <kamil@samigullin.info>
 */
class ConfigResolverTest extends TestCase
{
    /**
     * @test
     */
    public function resolve()
    {
        $config = (new YamlConfig(new YamlFileLoader(new FileLocator(), new SymfonyYamlParser())))
            ->load($this->getConfigPath('monolog/config'))
            ->replace(['root_dir' => dirname(__DIR__)])
            ->toArray()
        ;
        $resolver = new ConfigResolver();
        $resolver->resolve($config['monolog']);
        self::assertCount(2, $resolver->getHandlers()->keys());
        self::assertCount(3, $resolver->getProcessors());
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     */
    public function throwInvalidArgumentExceptionByResolve()
    {
        // deprecated BC will be removed in v2.0
        $app = new \Pimple();
        $resolver = new ConfigResolver($app);
        $resolver->resolve([
            'handlers' => [
                'stream' => [
                    'type' => 'stream',
                ],
            ],
        ]);
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     */
    public function throwInvalidArgumentExceptionByGetClass()
    {
        // deprecated BC will be removed in v2.0
        $app = new \Pimple();
        $resolver = new ConfigResolver($app);
        $resolver->resolve([
            'handlers' => [
                'stream' => [],
            ],
        ]);
    }
}
