<?php

namespace OctoLab\Cilex\Tests\Config;

use OctoLab\Cilex\Config\SimpleConfig;
use OctoLab\Cilex\Tests\TestCase;

/**
 * phpunit src/Tests/Config/SimpleConfigTest.php
 *
 * @author Kamil Samigullin <kamil@samigullin.info>
 */
class SimpleConfigTest extends TestCase
{
    /**
     * @test
     */
    public function replace()
    {
        $config = new SimpleConfig(include $this->getConfigPath('config', 'php'));
        self::assertNotEmpty($config->replace(['placeholder' => 'placeholder'])->toArray());
    }
}
