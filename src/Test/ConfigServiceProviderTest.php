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
    public function testComplexBehavior()
    {
        $app = new Application('Test');
        $app->register(new ConfigServiceProvider(__DIR__ . '/app/config/config.yml', ['placeholder' => 'placeholder']));
        $expected = [
            'component' => [
                'base_parameter' => 'base parameter will not be overwritten',
                'parameter' => "base component's parameter will be overwritten by root config",
                'placeholder_parameter' => 'placeholder',
                'internal_parameter' => "component's parameter will not be overwritten",
            ],
            'parameters' => [
                'parameter' => "component's parameter will be overwritten",
                'prefixed_parameter' => "component's parameter will not be overwritten",
            ],
        ];
        $this->assertEquals($expected, $app['config']);
    }
}
