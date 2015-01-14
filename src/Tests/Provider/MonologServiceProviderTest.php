<?php
/**
 * @link http://www.octolab.org/
 * @copyright Copyright (c) 2013 OctoLab
 * @license http://www.octolab.org/license
 */

namespace OctoLab\Cilex\Tests\Provider;

use Cilex\Application;
use OctoLab\Cilex\Provider\ConfigServiceProvider;
use OctoLab\Cilex\Provider\MonologServiceProvider;

/**
 * @author Kamil Samigullin <kamil@samigullin.info>
 */
class MonologServiceProviderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @return ConfigServiceProvider[]
     */
    public function configProvider()
    {
        return [
            [new ConfigServiceProvider($this->getConfigPath('config'), ['root_dir' => realpath(dirname(__DIR__))])],
        ];
    }

    /**
     * @test
     * @dataProvider configProvider
     *
     * @param ConfigServiceProvider $config
     */
    public function configSupportBehavior(ConfigServiceProvider $config)
    {
        $app = new Application('Test');
        $app->register($config);
        $app->register(new MonologServiceProvider());
        $logs = [
            $app['config']['monolog']['handlers']['access']['path'],
            $app['config']['monolog']['handlers']['error']['path'],
        ];
        $messages = [
            'Info level message.',
            'Error level message.',
        ];
        $monolog = $app['monolog'];
        $monolog->info($messages[0]);
        $monolog->error($messages[1]);
        $this->assertContains($messages[0], file_get_contents($logs[0]));
        $this->assertContains($messages[1], file_get_contents($logs[1]));
        foreach ($logs as $log) {
            unlink($log);
        }
    }

    /**
     * @test
     * @dataProvider configProvider
     *
     * @param ConfigServiceProvider $config
     */
    public function nameSupportBehavior(ConfigServiceProvider $config)
    {
        $appName = 'TEST';
        // set name by default way
        $app = new Application($appName);
        $app->register($config);
        $log = $app['config']['monolog']['handlers']['access']['path'];
        $app['monolog.name'] = 'MONOLOG';
        $app->register(new MonologServiceProvider());
        $monolog = $app['monolog'];
        $monolog->info('message');
        $this->assertNotContains($appName, file_get_contents($log));
        $this->assertContains($app['monolog.name'], file_get_contents($log));
        unlink($log);
        // set name by Application
        $app = new Application($appName);
        $app->register($config);
        $app->register(new MonologServiceProvider());
        $monolog = $app['monolog'];
        $monolog->info('message');
        $this->assertContains($appName, file_get_contents($log));
        unlink($log);
        // set name by config
        $app = new Application($appName);
        $app->register($config);
        $config = $app['config'];
        $config['monolog']['name'] = 'CONFIG';
        $app['config'] = $config;
        $app->register(new MonologServiceProvider());
        $monolog = $app['monolog'];
        $monolog->info('message');
        $this->assertNotContains($appName, file_get_contents($log));
        $this->assertContains($app['config']['monolog']['name'], file_get_contents($log));
        unlink($log);
    }

    /**
     * @param string $config
     *
     * @return string
     */
    private function getConfigPath($config)
    {
        return sprintf('%s/app/monolog/%s.yml', realpath(dirname(__DIR__)), $config);
    }
}
