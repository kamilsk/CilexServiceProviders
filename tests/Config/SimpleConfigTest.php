<?php

namespace Test\OctoLab\Cilex\Config;

use OctoLab\Cilex\Config\SimpleConfig;
use Test\OctoLab\Cilex\TestCase;

/**
 * phpunit tests/Config/SimpleConfigTest.php
 *
 * @author Kamil Samigullin <kamil@samigullin.info>
 */
class SimpleConfigTest extends TestCase
{
    /**
     * @test
     * @dataProvider simpleConfigProvider
     *
     * @param SimpleConfig $config
     */
    public function replace(SimpleConfig $config)
    {
        self::assertNotEmpty($config->replace(['placeholder' => 'placeholder'])->toArray());
    }

    /**
     * @test
     * @dataProvider simpleConfigProvider
     *
     * @param SimpleConfig $config
     */
    public function toArray(SimpleConfig $config)
    {
        self::assertNotEmpty($config->toArray());
    }

    /**
     * @return array[]
     */
    public function simpleConfigProvider()
    {
        return [
            [new SimpleConfig(include $this->getConfigPath('config', 'php'))],
        ];
    }
}
