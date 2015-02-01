<?php
/**
 * @link http://www.octolab.org/
 * @copyright Copyright (c) 2013 OctoLab
 * @license http://www.octolab.org/license
 */

namespace OctoLab\Cilex\Tests\Command;

use Cilex\Application;
use OctoLab\Cilex\Command\Command;
use OctoLab\Cilex\Provider\ConfigServiceProvider;
use OctoLab\Cilex\Provider\DoctrineServiceProvider;
use OctoLab\Cilex\Provider\MonologServiceProvider;
use OctoLab\Cilex\Tests\TestCase;

/**
 * @author Kamil Samigullin <kamil@samigullin.info>
 */
class CommandTest extends TestCase
{
    /**
     * @test
     */
    public function commandNamespace()
    {
        $command = new MockCommand();
        $this->assertEquals('test', $command->getName());
        $command = new MockCommand('mock');
        $this->assertEquals('mock:test', $command->getName());
    }

    /**
     * @test
     * @expectedException \RuntimeException
     */
    public function getDbConnectionFail()
    {
        $app = new Application('Test');
        $command = new MockCommand();
        $app->command($command);
        $this->assertInstanceOf('\Doctrine\DBAL\Connection', $command->getDbConnection());
    }

    /**
     * @test
     * @dataProvider doctrineConfigProvider
     *
     * @param ConfigServiceProvider $config
     */
    public function getDbConnectionSuccess(ConfigServiceProvider $config)
    {
        $app = new Application('Test');
        $app->register($config);
        $app->register(new DoctrineServiceProvider());
        $command = new MockCommand();
        $app->command($command);
        $this->assertInstanceOf('\Doctrine\DBAL\Connection', $command->getDbConnection());
    }

    /**
     * @test
     * @expectedException \RuntimeException
     */
    public function getLoggerFail()
    {
        $app = new Application('Test');
        $command = new MockCommand();
        $app->command($command);
        $this->assertInstanceOf('\Monolog\Logger', $command->getLogger());
    }

    /**
     * @test
     * @dataProvider monologConfigProvider
     *
     * @param ConfigServiceProvider $config
     */
    public function getLoggerSuccess(ConfigServiceProvider $config)
    {
        $app = new Application('Test');
        $app->register($config);
        $app->register(new MonologServiceProvider());
        $command = new MockCommand();
        $app->command($command);
        $this->assertInstanceOf('\Monolog\Logger', $command->getLogger());
    }
}

/**
 * @author Kamil Samigullin <kamil@samigullin.info>
 */
class MockCommand extends Command
{
    protected function configure()
    {
        $this->setName('test');
    }
}
