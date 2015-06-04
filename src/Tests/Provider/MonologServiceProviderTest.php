<?php

namespace OctoLab\Cilex\Tests\Provider;

use Cilex\Application;
use OctoLab\Cilex\Provider\ConfigServiceProvider;
use OctoLab\Cilex\Provider\MonologServiceProvider;
use OctoLab\Cilex\Tests\TestCase;

/**
 * phpunit src/Tests/Provider/MonologServiceProviderTest.php
 *
 * @author Kamil Samigullin <kamil@samigullin.info>
 */
class MonologServiceProviderTest extends TestCase
{
    /**
     * @test
     * @dataProvider monologConfigProvider
     *
     * @param ConfigServiceProvider $config
     */
    public function configSupport(ConfigServiceProvider $config)
    {
        $app = new Application('Test');
        $app->register($config);
        $app->register(new MonologServiceProvider(false));
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
        self::assertContains($messages[0], file_get_contents($logs[0]));
        self::assertContains($messages[1], file_get_contents($logs[1]));
        foreach ($logs as $log) {
            unlink($log);
        }
    }

    /**
     * @test
     * @dataProvider monologConfigProvider
     *
     * @param ConfigServiceProvider $config
     */
    public function nameSupport(ConfigServiceProvider $config)
    {
        $appName = 'TEST';
        // set name by default way
        $app = new Application($appName);
        $app->register($config);
        $log = $app['config']['monolog']['handlers']['access']['path'];
        $app['monolog.name'] = 'MONOLOG';
        $app->register(new MonologServiceProvider(false));
        $monolog = $app['monolog'];
        $monolog->info('message');
        self::assertNotContains($appName, file_get_contents($log));
        self::assertContains($app['monolog.name'], file_get_contents($log));
        unlink($log);
        // set name by Application
        $app = new Application($appName);
        $app->register($config);
        $app->register(new MonologServiceProvider(false));
        $monolog = $app['monolog'];
        $monolog->info('message');
        self::assertContains($appName, file_get_contents($log));
        unlink($log);
        // set name by config
        $app = new Application($appName);
        $app->register($config);
        $config = $app['config'];
        $config['monolog']['name'] = 'CONFIG';
        $app['config'] = $config;
        $app->register(new MonologServiceProvider(false));
        $monolog = $app['monolog'];
        $monolog->info('message');
        self::assertNotContains($appName, file_get_contents($log));
        self::assertContains($app['config']['monolog']['name'], file_get_contents($log));
        unlink($log);
    }

    /**
     * @test
     * @dataProvider monologConfigProvider
     *
     * @param ConfigServiceProvider $config
     */
    public function aliasSupport(ConfigServiceProvider $config)
    {
        $app = new Application('Test');
        $app->register($config);
        $app->register(new MonologServiceProvider());
        self::assertInstanceOf('\Monolog\Logger', $app['monolog']);
        self::assertInstanceOf('\Psr\Log\LoggerInterface', $app['logger']);
    }
}
