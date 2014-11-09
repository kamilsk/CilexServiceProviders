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
     * @test
     */
    public function configSupportBehavior()
    {
        $app = new Application('Test');
        $app->register(new ConfigServiceProvider(__DIR__ . '/../app/monolog/config.yml', ['root_dir' => __DIR__ . '/../']));
        $app->register(new MonologServiceProvider());
        $logs = [
            $app['config']['monolog']['handlers']['access']['path'],
            $app['config']['monolog']['handlers']['error']['path'],
        ];
        $messages = [
            'Info level message.',
            'Error level message.',
        ];
        /** @var \Psr\Log\LoggerInterface $monolog */
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
     */
    public function nameSupportBehavior()
    {
        $appName = 'TEST';
        // set name by default way
        $app = new Application($appName);
        $app->register(new ConfigServiceProvider(__DIR__ . '/../app/monolog/config.yml', ['root_dir' => __DIR__ . '/../']));
        $log = $app['config']['monolog']['handlers']['access']['path'];
        $app['monolog.name'] = 'MONOLOG';
        $app->register(new MonologServiceProvider());
        /** @var \Psr\Log\LoggerInterface $monolog */
        $monolog = $app['monolog'];
        $monolog->info('message');
        $this->assertNotContains($appName, file_get_contents($log));
        $this->assertContains($app['monolog.name'], file_get_contents($log));
        unlink($log);
        // set name by Application
        $app = new Application($appName);
        $app->register(new ConfigServiceProvider(__DIR__ . '/../app/monolog/config.yml', ['root_dir' => __DIR__ . '/../']));
        $app->register(new MonologServiceProvider());
        $monolog = $app['monolog'];
        $monolog->info('message');
        $this->assertContains($appName, file_get_contents($log));
        unlink($log);
        // set name by config
        $app = new Application($appName);
        $app->register(new ConfigServiceProvider(__DIR__ . '/../app/monolog/config.yml', ['root_dir' => __DIR__ . '/../']));
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
}
