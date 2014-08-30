<?php
/**
 * @link http://www.octolab.org/
 * @copyright Copyright (c) 2013 OctoLab
 * @license http://www.octolab.org/license
 */

namespace OctoLab\Cilex\Test;

use Cilex\Application;
use OctoLab\Cilex\Provider\ConfigServiceProvider;
use OctoLab\Cilex\Provider\MonologServiceProvider;

/**
 * @author Kamil Samigullin <kamil@samigullin.info>
 */
class MonologServiceProviderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @return Application[]
     */
    public function applicationProvider()
    {
        return [
            [new Application('Test')],
        ];
    }

    /**
     * @test
     * @dataProvider applicationProvider
     *
     * @param Application $app
     */
    public function configSupportBehavior(Application $app)
    {
        $app->register(new ConfigServiceProvider(__DIR__ . '/app/monolog/config.yml', ['root_dir' => __DIR__]));
        $app->register(new MonologServiceProvider());
        $logs = [
            $app['config']['monolog']['handlers']['access']['path'],
            $app['config']['monolog']['handlers']['error']['path'],
        ];
        $messages = [
            'Info level message.',
            'Error level message.',
        ];
        /** @var \Monolog\Logger $monolog */
        $monolog = $app['monolog'];
        $monolog->addInfo($messages[0]);
        $monolog->addError($messages[1]);
        $this->assertContains($messages[0], file_get_contents($logs[0]));
        $this->assertContains($messages[1], file_get_contents($logs[1]));
        foreach ($logs as $log) {
            unlink($log);
        }
    }
}
