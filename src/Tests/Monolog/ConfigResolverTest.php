<?php

namespace OctoLab\Cilex\Tests\Monolog;

use Monolog\Formatter\JsonFormatter;
use OctoLab\Cilex\Config\Loader\YamlFileLoader;
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
        $config = (new YamlConfig(new YamlFileLoader(new FileLocator())))
            ->load($this->getConfigPath('monolog/resolver'))
            ->replace(['root_dir' => dirname(__DIR__)])
            ->toArray()
        ;
        // deprecated BC will be removed in v2.0
        $app = new \Pimple();
        $app['json'] = new JsonFormatter();
        $resolver = new ConfigResolver($app);
        $resolver->resolve($config['monolog']);
        self::assertCount(2, $resolver->getHandlers()->keys());
        self::assertCount(3, $resolver->getProcessors());
    }
}
